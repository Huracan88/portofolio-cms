<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SettingSeeder extends Seeder
{
    /**
     * @var array<string, array{value: string, encrypted: bool}>
     */
    private const DEFAULTS = [
        'openrouter_key' => ['value' => '', 'encrypted' => true],
        'openrouter_translate_model' => ['value' => 'google/gemini-3.1-flash', 'encrypted' => false],
        'openrouter_image_model' => ['value' => 'google/gemini-3.0-nano-banana', 'encrypted' => false],
    ];

    public function run(): void
    {
        foreach (self::DEFAULTS as $key => $config) {
            if (Setting::query()->whereKey($key)->exists()) {
                continue;
            }

            Setting::create([
                'key' => $key,
                'value' => $config['encrypted'] ? Crypt::encryptString($config['value']) : $config['value'],
                'is_encrypted' => $config['encrypted'],
            ]);

            Cache::forget("settings.{$key}");
        }
    }
}
