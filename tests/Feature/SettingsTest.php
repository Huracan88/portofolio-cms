<?php

use App\Models\Setting;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
});

it('returns the default when the key does not exist', function () {
    expect(Setting::get('missing.key', 'fallback'))->toBe('fallback');
    expect(Setting::get('missing.key'))->toBeNull();
});

it('stores and retrieves a plain value', function () {
    Setting::set('site_name', 'Portfolio');

    expect(Setting::get('site_name'))->toBe('Portfolio');
});

it('stores an encrypted value without plaintext in the database', function () {
    Setting::set('openrouter_key', 'sk-secret-123', true);

    $row = Setting::query()->whereKey('openrouter_key')->first();

    expect($row->is_encrypted)->toBeTrue();
    expect($row->value)->not->toContain('sk-secret-123');
    expect(Crypt::decryptString($row->value))->toBe('sk-secret-123');
    expect(Setting::get('openrouter_key'))->toBe('sk-secret-123');
});

it('invalidates the cache when a value is updated', function () {
    Setting::set('site_name', 'First');
    expect(Setting::get('site_name'))->toBe('First');

    Setting::set('site_name', 'Second');

    expect(Setting::get('site_name'))->toBe('Second');
});

it('returns the default when an encrypted value cannot be decrypted', function () {
    Setting::create([
        'key' => 'broken',
        'value' => 'not-a-valid-ciphertext',
        'is_encrypted' => true,
    ]);

    expect(Setting::get('broken', 'safe'))->toBe('safe');
});

it('seeds the defaults without overwriting existing values', function () {
    Setting::set('openrouter_translate_model', 'custom/model');

    $this->seed(SettingSeeder::class);

    expect(Setting::get('openrouter_translate_model'))->toBe('custom/model');
    expect(Setting::get('openrouter_image_model'))->toBe('google/gemini-3.0-nano-banana');
    expect(Setting::get('openrouter_key'))->toBe('');
});
