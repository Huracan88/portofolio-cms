<?php

namespace App\Services;

use App\Exceptions\MissingOpenRouterKeyException;
use App\Exceptions\OpenRouterException;
use App\Models\Setting;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    public function translate(string $text, string $from, string $to, ?string $model = null, bool $isHtml = false): string
    {
        $text = trim($text);

        if (blank($text)) {
            throw new OpenRouterException('The text to translate cannot be empty.');
        }

        $key = $this->apiKey();
        $model = $model ?? Setting::get('openrouter_translate_model') ?? config('openrouter.translate_model');

        $prompt = $isHtml
            ? "Translate the HTML fragment below from {$from} to {$to}. Preserve the exact HTML markup, tags, attributes and structure; only translate the visible text content. Do not wrap the result in code blocks or add any explanation. Return only the translated HTML.\n\n{$text}"
            : "Translate the text below from {$from} to {$to}. Return only the translation, without explanations, quotes or notes.\n\n{$text}";

        try {
            $response = $this->request($key, (int) config('openrouter.translate_timeout'))
                ->post('chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a professional translator.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.2,
                ]);
        } catch (ConnectionException $exception) {
            throw new OpenRouterException('OpenRouter connection failed: '.$exception->getMessage(), previous: $exception);
        }

        $this->assertSuccessful($response, 'translation');

        $content = data_get($response->json(), 'choices.0.message.content');

        if (! is_string($content) || blank(trim($content))) {
            throw new OpenRouterException('OpenRouter returned an empty translation.');
        }

        return trim($content);
    }

    /**
     * @return array<int, array{binary: string, media_type: string}>
     */
    public function generateImages(string $prompt, ?string $model = null, string $aspectRatio = '1:1', int $n = 1): array
    {
        if (blank(trim($prompt))) {
            throw new OpenRouterException('The image prompt cannot be empty.');
        }

        $key = $this->apiKey();
        $model = $model ?? Setting::get('openrouter_image_model') ?? config('openrouter.image_model');

        try {
            $response = $this->request($key, (int) config('openrouter.image_timeout'))
                ->post('images', [
                    'model' => $model,
                    'prompt' => $prompt,
                    'n' => max(1, min(4, $n)),
                    'aspect_ratio' => $aspectRatio,
                    'output_format' => 'png',
                ]);
        } catch (ConnectionException $exception) {
            throw new OpenRouterException('OpenRouter connection failed: '.$exception->getMessage(), previous: $exception);
        }

        $this->assertSuccessful($response, 'image generation');

        $images = [];

        foreach ($response->json('data') ?? [] as $item) {
            $mediaType = is_array($item) ? (string) ($item['media_type'] ?? 'image/png') : 'image/png';

            $binary = null;

            if (is_array($item) && filled($item['b64_json'] ?? null)) {
                $binary = base64_decode((string) $item['b64_json'], true);
            }

            if ($binary === false || $binary === null) {
                $url = is_array($item) ? ($item['url'] ?? null) : null;

                if (! is_string($url) || blank($url)) {
                    continue;
                }

                try {
                    $download = Http::timeout((int) config('openrouter.image_timeout'))->get($url);
                } catch (ConnectionException $exception) {
                    throw new OpenRouterException('Failed to download a generated image: '.$exception->getMessage(), previous: $exception);
                }

                if (! $download->ok()) {
                    continue;
                }

                $binary = $download->body();
            }

            if (filled($binary)) {
                $images[] = ['binary' => $binary, 'media_type' => $mediaType];
            }
        }

        if ($images === []) {
            throw new OpenRouterException('OpenRouter returned no usable images.');
        }

        $cost = data_get($response->json(), 'usage.cost');

        if (is_numeric($cost)) {
            Log::info('OpenRouter image generation completed', [
                'model' => $model,
                'count' => count($images),
                'cost' => (float) $cost,
            ]);
        }

        return $images;
    }

    private function apiKey(): string
    {
        $key = Setting::get('openrouter_key');

        if (blank($key)) {
            throw new MissingOpenRouterKeyException('OpenRouter API key is not configured. Add it in the Settings page first.');
        }

        return $key;
    }

    private function request(string $key, int $timeout): PendingRequest
    {
        return Http::baseUrl((string) config('openrouter.base_uri'))
            ->withToken($key)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'HTTP-Referer' => (string) config('openrouter.referer'),
                'X-OpenRouter-Title' => (string) config('openrouter.app_title'),
            ])
            ->acceptJson()
            ->timeout($timeout);
    }

    private function assertSuccessful(Response $response, string $operation): void
    {
        if ($response->successful()) {
            return;
        }

        $error = data_get($response->json(), 'error.message');
        $message = is_string($error) ? $error : $response->body();

        throw new OpenRouterException("OpenRouter {$operation} failed (HTTP {$response->status()}): {$message}");
    }
}
