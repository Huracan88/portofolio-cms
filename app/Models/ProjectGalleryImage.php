<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $project_id
 * @property int $media_id
 * @property string|null $caption_es
 * @property string|null $caption_en
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Project $project
 * @property-read Media|null $media
 * @property-read string|null $caption
 * @property-read string|null $url
 * @property-read string|null $relative_url
 * @property-read string|null $alt
 */
class ProjectGalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'media_id', 'caption_es', 'caption_en', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function getCaptionAttribute(): ?string
    {
        $caption = $this->{'caption_'.app()->getLocale()} ?? $this->caption_en;

        return filled($caption) ? $caption : null;
    }

    public function getUrlAttribute(): ?string
    {
        return $this->media?->url;
    }

    public function getRelativeUrlAttribute(): ?string
    {
        return $this->media?->relative_url;
    }

    public function getAltAttribute(): ?string
    {
        if ($this->media) {
            $alt = $this->media->{'alt_'.app()->getLocale()} ?? $this->media->alt_en;

            if (filled($alt)) {
                return $alt;
            }
        }

        return $this->caption;
    }
}
