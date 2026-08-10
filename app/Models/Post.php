<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $author_id
 * @property int|null $category_id
 * @property string $title_es
 * @property string $title_en
 * @property string $slug
 * @property string|null $excerpt_es
 * @property string|null $excerpt_en
 * @property string|null $body_es
 * @property string|null $body_en
 * @property string|null $cover_image_url
 * @property bool $is_published
 * @property bool $is_featured
 * @property Carbon|null $published_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $title
 * @property-read string|null $excerpt
 * @property-read string|null $body
 * @property-read User $author
 * @property-read Category|null $category
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id', 'category_id', 'title_es', 'title_en', 'slug',
        'excerpt_es', 'excerpt_en', 'body_es', 'body_en', 'cover_image_url',
        'is_published', 'is_featured', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
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

    public function getBodyAttribute(): ?string
    {
        return $this->{'body_'.app()->getLocale()} ?? $this->body_en;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    protected static function booted(): void
    {
        static::creating(function (self $post): void {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title_en);
            }
        });

        static::updating(function (self $post): void {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title_en);
            }
        });
    }
}
