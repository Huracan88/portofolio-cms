<?php

namespace App\Models;

use App\Services\MediaService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $original_name
 * @property string $file_name
 * @property string $disk
 * @property string $path
 * @property string $mime_type
 * @property string $extension
 * @property int $size
 * @property int|null $width
 * @property int|null $height
 * @property string|null $alt_es
 * @property string|null $alt_en
 * @property string|null $collection
 * @property Carbon|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $url
 * @property-read string $relative_url
 * @property-read string $human_size
 * @property-read bool $is_processable
 * @property-read User|null $user
 */
class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'original_name', 'file_name', 'disk', 'path', 'mime_type',
        'extension', 'size', 'width', 'height', 'alt_es', 'alt_en', 'collection',
    ];

    protected function casts(): array
    {
        return [
            'width' => 'integer',
            'height' => 'integer',
            'size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getRelativeUrlAttribute(): string
    {
        return 'storage/'.$this->path;
    }

    public function getHumanSizeAttribute(): string
    {
        return Number::fileSize($this->size);
    }

    public function getIsProcessableAttribute(): bool
    {
        if ($this->mime_type === 'image/gif') {
            return false;
        }

        return $this->mime_type !== 'image/avif' || function_exists('imagecreatefromavif');
    }

    protected static function booted(): void
    {
        // Force-deleting also removes the physical file, covering bulk force deletes.
        static::forceDeleted(function (self $media): void {
            app(MediaService::class)->deleteFile($media);
        });
    }
}
