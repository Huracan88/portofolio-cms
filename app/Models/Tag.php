<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name_es
 * @property string $name_en
 * @property string $slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $name
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_es', 'name_en', 'slug',
    ];

    public function getNameAttribute(): string
    {
        return $this->{'name_'.app()->getLocale()} ?? $this->name_en;
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }

    protected static function booted(): void
    {
        static::creating(function (self $tag): void {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name_en);
            }
        });

        static::updating(function (self $tag): void {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name_en);
            }
        });
    }
}
