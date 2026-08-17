<?php

use App\Exceptions\MissingOpenRouterKeyException;
use App\Exceptions\OpenRouterException;
use App\Models\Setting;
use App\Services\OpenRouterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    Setting::set('openrouter_key', 'test-key');
    $this->service = app(OpenRouterService::class);
});

it('translates text via the chat completions endpoint', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'choices' => [['message' => ['content' => 'Hola mundo']]],
        ]),
    ]);

    $translated = $this->service->translate('Hello world', 'en', 'es');

    expect($translated)->toBe('Hola mundo');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://openrouter.ai/api/v1/chat/completions'
            && $request['model'] === 'google/gemini-3.1-flash'
            && $request['temperature'] === 0.2
            && $request['messages'][1]['content'] === "Translate the text below from en to es. Return only the translation, without explanations, quotes or notes.\n\nHello world"
            && $request->hasHeader('Authorization', 'Bearer test-key')
            && $request->hasHeader('HTTP-Referer', config('app.url'))
            && $request->hasHeader('X-OpenRouter-Title', 'Portfolio CMS');
    });
});

it('translates html fragments asking to preserve the markup', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'choices' => [['message' => ['content' => '<p>Hola</p>']]],
        ]),
    ]);

    $translated = $this->service->translate('<p>Hello</p>', 'en', 'es', isHtml: true);

    expect($translated)->toBe('<p>Hola</p>');

    Http::assertSent(fn ($request): bool => str_contains($request['messages'][1]['content'], 'Preserve the exact HTML markup'));
});

it('uses the explicit model when provided', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'choices' => [['message' => ['content' => 'ok']]],
        ]),
    ]);

    $this->service->translate('Hello', 'en', 'es', model: 'custom/model');

    Http::assertSent(fn ($request): bool => $request['model'] === 'custom/model');
});

it('throws when the translation request fails with an http error', function (int $status) {
    Http::fake([
        'openrouter.ai/*' => Http::response(['error' => ['message' => 'Something went wrong']], $status),
    ]);

    expect(fn () => $this->service->translate('Hello', 'en', 'es'))
        ->toThrow(OpenRouterException::class, "OpenRouter translation failed (HTTP {$status}): Something went wrong");
})->with([401, 429, 500]);

it('throws when the api key is missing without making any request', function () {
    Setting::set('openrouter_key', '');
    Http::fake();

    expect(fn () => $this->service->translate('Hello', 'en', 'es'))
        ->toThrow(MissingOpenRouterKeyException::class);

    Http::assertNothingSent();
});

it('throws when the connection times out', function () {
    Http::fake([
        'openrouter.ai/*' => function () {
            throw new ConnectionException('Connection timed out');
        },
    ]);

    expect(fn () => $this->service->translate('Hello', 'en', 'es'))
        ->toThrow(OpenRouterException::class, 'OpenRouter connection failed: Connection timed out');
});

it('throws when the translation is empty', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'choices' => [['message' => ['content' => '   ']]],
        ]),
    ]);

    expect(fn () => $this->service->translate('Hello', 'en', 'es'))
        ->toThrow(OpenRouterException::class, 'OpenRouter returned an empty translation.');
});

it('throws when the text to translate is empty', function () {
    expect(fn () => $this->service->translate('  ', 'en', 'es'))
        ->toThrow(OpenRouterException::class, 'The text to translate cannot be empty.');
});

it('generates images from base64 payloads', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'data' => [
                ['b64_json' => base64_encode('fake-binary-1'), 'media_type' => 'image/png'],
                ['b64_json' => base64_encode('fake-binary-2'), 'media_type' => 'image/png'],
            ],
            'usage' => ['cost' => 0.0042],
        ]),
    ]);

    $images = $this->service->generateImages('A red panda', aspectRatio: '16:9', n: 2);

    expect($images)->toBe([
        ['binary' => 'fake-binary-1', 'media_type' => 'image/png'],
        ['binary' => 'fake-binary-2', 'media_type' => 'image/png'],
    ]);

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://openrouter.ai/api/v1/images'
            && $request['model'] === 'google/gemini-3.0-nano-banana'
            && $request['prompt'] === 'A red panda'
            && $request['n'] === 2
            && $request['aspect_ratio'] === '16:9'
            && $request['output_format'] === 'png';
    });
});

it('falls back to downloading the image url when no base64 payload is present', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'data' => [
                ['url' => 'https://example.com/generated.png', 'media_type' => 'image/png'],
            ],
        ]),
        'example.com/*' => Http::response('downloaded-bytes'),
    ]);

    $images = $this->service->generateImages('A red panda');

    expect($images)->toBe([
        ['binary' => 'downloaded-bytes', 'media_type' => 'image/png'],
    ]);
});

it('throws when the image request fails', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response(['error' => ['message' => 'Insufficient credits']], 402),
    ]);

    expect(fn () => $this->service->generateImages('A red panda'))
        ->toThrow(OpenRouterException::class, 'OpenRouter image generation failed (HTTP 402): Insufficient credits');
});

it('throws when the api returns no usable images', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'data' => [
                ['url' => 'https://example.com/missing.png'],
            ],
        ]),
        'example.com/*' => Http::response('', 404),
    ]);

    expect(fn () => $this->service->generateImages('A red panda'))
        ->toThrow(OpenRouterException::class, 'OpenRouter returned no usable images.');
});

it('throws when the image prompt is empty', function () {
    expect(fn () => $this->service->generateImages(''))
        ->toThrow(OpenRouterException::class, 'The image prompt cannot be empty.');
});
