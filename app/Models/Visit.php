<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string|null $ip_hash
 * @property string $path
 * @property string|null $route_name
 * @property string $method
 * @property string|null $referer
 * @property string|null $referer_host
 * @property string $country_code
 * @property string $country_name
 * @property string $device_type
 * @property string|null $browser
 * @property string|null $platform
 * @property string|null $locale
 * @property Carbon $visited_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $country_flag
 */
class Visit extends Model
{
    use HasFactory;
    use MassPrunable;

    protected $fillable = [
        'ip_hash',
        'path',
        'route_name',
        'method',
        'referer',
        'referer_host',
        'country_code',
        'country_name',
        'device_type',
        'browser',
        'platform',
        'locale',
        'visited_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    /**
     * Get the prunable model query (90 days retention).
     *
     * @return Builder<Visit>
     */
    public function prunable(): Builder
    {
        return static::where('visited_at', '<', now()->subDays(90));
    }

    /**
     * Get flag emoji for ISO country code or fallback symbol.
     */
    public function getCountryFlagAttribute(): string
    {
        $code = strtoupper(trim($this->country_code));

        if ($code === 'LOCAL') {
            return '💻';
        }

        if (strlen($code) !== 2 || ! ctype_alpha($code)) {
            return '🌐';
        }

        // Convert ISO 3166-1 alpha-2 code to Regional Indicator Symbol emoji
        $firstChar = ord($code[0]) - ord('A') + 0x1F1E6;
        $secondChar = ord($code[1]) - ord('A') + 0x1F1E6;

        return mb_chr($firstChar, 'UTF-8').mb_chr($secondChar, 'UTF-8');
    }
}
