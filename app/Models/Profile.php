<?php

namespace App\Models;

use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $full_name
 * @property string $title
 * @property string $email
 * @property string|null $phone
 * @property string|null $location
 * @property string $bio_es
 * @property string $bio_en
 * @property string|null $photo_url
 * @property string|null $license_number
 * @property string|null $availability
 * @property array|null $social_links
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $bio
 */
class Profile extends Model
{
    /** @use HasFactory<ProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name', 'title', 'email', 'phone', 'location',
        'bio_es', 'bio_en', 'photo_url', 'license_number',
        'availability', 'social_links',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
        ];
    }

    public function getBioAttribute(): string
    {
        return $this->{'bio_'.app()->getLocale()} ?? $this->bio_es;
    }

    public static function getSingleton(): ?self
    {
        return static::first();
    }
}
