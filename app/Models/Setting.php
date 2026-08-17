<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

/**
 * @property string $key
 * @property string|null $value
 * @property bool $is_encrypted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['key', 'value', 'is_encrypted'];

    protected function casts(): array
    {
        return [
            'is_encrypted' => 'boolean',
        ];
    }

    /**
     * Read a setting, decrypting the value in memory when the row is encrypted.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $cached = Cache::rememberForever("settings.{$key}", function () use ($key): array {
            return static::query()->whereKey($key)->first()
                ?->only(['value', 'is_encrypted'])
                ?? ['value' => null, 'is_encrypted' => false];
        });

        $value = $cached['value'] ?? null;

        if ($value === null) {
            return $default;
        }

        if (($cached['is_encrypted'] ?? false) && filled($value)) {
            try {
                $value = Crypt::decryptString($value);
            } catch (DecryptException) {
                return $default;
            }
        }

        return $value;
    }

    public static function set(string $key, ?string $value, bool $encrypted = false): void
    {
        $stored = $value === null
            ? null
            : ($encrypted ? Crypt::encryptString($value) : $value);

        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $stored, 'is_encrypted' => $encrypted],
        );

        Cache::forget("settings.{$key}");
    }
}
