<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $title_es
 * @property string $title_en
 * @property string $slug
 * @property string|null $excerpt_es
 * @property string|null $excerpt_en
 * @property string|null $description_es
 * @property string|null $description_en
 * @property string|null $image_url
 * @property string|null $project_url
 * @property string|null $repo_url
 * @property string|null $sector
 * @property bool $is_featured
 * @property bool $is_visible
 * @property Carbon|null $published_at
 * @property int $sort_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $title
 * @property-read string|null $excerpt
 * @property-read string|null $description
 */
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_es', 'title_en', 'slug', 'excerpt_es', 'excerpt_en',
        'description_es', 'description_en', 'image_url', 'project_url',
        'repo_url', 'sector', 'is_featured', 'is_visible', 'published_at', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public function getTitleAttribute(): string
    {
        return $this->{'title_'.app()->getLocale()} ?? $this->title_en;
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->{'excerpt_'.app()->getLocale()} ?? $this->excerpt_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->{'description_'.app()->getLocale()} ?? $this->description_en;
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'project_skill');
    }

    protected static function booted(): void
    {
        static::creating(function (self $project): void {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title_en);
            }
        });

        static::updating(function (self $project): void {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title_en);
            }
        });
    }
}
