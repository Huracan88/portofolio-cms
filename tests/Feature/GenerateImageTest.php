<?php

use App\Exceptions\MissingOpenRouterKeyException;
use App\Filament\Resources\MediaResource\Pages\ListMedia;
use App\Models\Media;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
    Storage::fake('public');
    $this->seed(RoleAndPermissionSeeder::class);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin', 'super_admin');
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Setting::set('openrouter_key', 'test-key');
});

function fakePngBinary(): string
{
    $image = imagecreatetruecolor(2, 2);
    ob_start();
    imagepng($image);
    $png = (string) ob_get_clean();
    imagedestroy($image);

    return $png;
}

test('generate image action stores webp files in the generated collection', function () {
    $png = fakePngBinary();

    Http::fake([
        'openrouter.ai/*' => Http::response([
            'data' => [
                ['b64_json' => base64_encode($png), 'media_type' => 'image/png'],
                ['b64_json' => base64_encode($png), 'media_type' => 'image/png'],
            ],
            'usage' => ['cost' => 0.0032],
        ]),
    ]);

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction('generateImage', [
            'prompt' => 'A red panda',
            'model' => 'model/x',
            'aspect_ratio' => '1:1',
            'n' => 2,
        ])
        ->assertHasNoFormErrors()
        ->assertNotified(__('Images generated successfully'));

    $media = Media::where('collection', 'generated')->get();

    expect($media)->toHaveCount(2);
    expect($media->pluck('mime_type')->all())->toBe(['image/webp', 'image/webp']);
    expect($media->pluck('extension')->all())->toBe(['webp', 'webp']);
    expect($media->pluck('user_id')->all())->toBe([$this->admin->id, $this->admin->id]);

    foreach ($media as $item) {
        expect(Storage::disk('public')->exists($item->path))->toBeTrue();
    }
});

test('generate image action sends the expected payload', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'data' => [['b64_json' => base64_encode(fakePngBinary()), 'media_type' => 'image/png']],
        ]),
    ]);

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction('generateImage', [
            'prompt' => 'A red panda',
            'model' => 'model/x',
            'aspect_ratio' => '16:9',
            'n' => 1,
        ]);

    Http::assertSent(fn ($request): bool => $request->url() === 'https://openrouter.ai/api/v1/images'
        && $request['model'] === 'model/x'
        && $request['aspect_ratio'] === '16:9'
        && $request['output_format'] === 'png'
        && $request->hasHeader('Authorization', 'Bearer test-key'));
});

test('generate image shows a danger notification and stores nothing when the api fails', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response(['error' => ['message' => 'Insufficient credits']], 402),
    ]);

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction('generateImage', [
            'prompt' => 'A red panda',
            'model' => 'model/x',
            'aspect_ratio' => '1:1',
            'n' => 1,
        ])
        ->assertNotified('OpenRouter image generation failed (HTTP 402): Insufficient credits');

    expect(Media::count())->toBe(0);
});

test('generate image shows a clear message when the api key is missing', function () {
    Setting::set('openrouter_key', '');

    Livewire::actingAs($this->admin)
        ->test(ListMedia::class)
        ->callAction('generateImage', [
            'prompt' => 'A red panda',
            'model' => 'model/x',
            'aspect_ratio' => '1:1',
            'n' => 1,
        ])
        ->assertNotified((new MissingOpenRouterKeyException('OpenRouter API key is not configured. Add it in the Settings page first.'))->getMessage());

    expect(Media::count())->toBe(0);
    Http::assertNothingSent();
});
