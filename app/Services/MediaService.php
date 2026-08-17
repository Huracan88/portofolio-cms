<?php

namespace App\Services;

use App\Exceptions\InvalidImageException;
use App\Exceptions\UnprocessableImageException;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    public function __construct(private readonly ImageProcessor $processor) {}

    /**
     * Store an uploaded image on disk and register it in the database.
     */
    public function store(UploadedFile $file, ?int $userId = null, ?string $collection = null): Media
    {
        if ($file->getSize() > (int) config('media.max_upload_kb') * 1024) {
            throw new InvalidImageException('The image exceeds the maximum allowed upload size.');
        }

        $sourcePath = $file->getRealPath();
        $mime = $this->processor->detectMime($sourcePath);

        $disk = (string) config('media.disk');
        $directory = (string) config('media.directory');

        $fileName = Str::uuid().'.'.$this->extensionForMime($mime);
        $path = $directory.'/'.$fileName;

        Storage::disk($disk)->putFileAs($directory, $file, $fileName);

        return $this->createMediaRecord($sourcePath, $file->getClientOriginalName(), $mime, $userId, $collection, $disk, $path);
    }

    /**
     * Store a raw image payload (e.g. AI generated) on disk and register it.
     *
     * The real mime is detected from the binary content; non-image payloads
     * throw InvalidImageException.
     */
    public function storeBinary(string $binary, string $mediaType, string $originalName, ?int $userId, string $collection): Media
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'media_');

        if ($tempPath === false) {
            throw new InvalidImageException('Unable to create a temporary file for the image.');
        }

        try {
            file_put_contents($tempPath, $binary);

            $mime = $this->processor->detectMime($tempPath);

            $disk = (string) config('media.disk');
            $directory = (string) config('media.directory');

            $fileName = Str::uuid().'.'.$this->extensionForMime($mime);
            $path = $directory.'/'.$fileName;

            Storage::disk($disk)->put($path, $binary);

            return $this->createMediaRecord($tempPath, $originalName, $mime, $userId, $collection, $disk, $path);
        } finally {
            @unlink($tempPath);
        }
    }

    /**
     * @param  non-empty-string  $sourcePath
     */
    private function createMediaRecord(
        string $sourcePath,
        string $originalName,
        string $mime,
        ?int $userId,
        ?string $collection,
        string $disk,
        string $path,
    ): Media {
        $dimensions = $this->processor->dimensions($sourcePath);

        return Media::create([
            'user_id' => $userId,
            'original_name' => $originalName,
            'file_name' => basename($path),
            'disk' => $disk,
            'path' => $path,
            'mime_type' => $mime,
            'extension' => $this->extensionForMime($mime),
            'size' => Storage::disk($disk)->size($path),
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
            'collection' => $collection,
        ]);
    }

    /**
     * Crop / resize / convert an existing media file.
     *
     * Overwrite mode keeps the same path (and URL); save-as-new mode writes a
     * fresh {uuid}.{ext} file and removes the previous one.
     *
     * @param  array{overwrite?: bool, crop?: array{x: int, y: int, width: int, height: int}, maxWidth?: int, quality?: int, asWebp?: bool}  $options
     */
    public function process(Media $media, array $options = []): Media
    {
        $overwrite = (bool) ($options['overwrite'] ?? false);
        $asWebp = (bool) ($options['asWebp'] ?? false);

        // Overwriting keeps the same path/URL; converting the format in place
        // would leave a file whose extension no longer matches its contents, so
        // WebP conversion only applies to save-as-new.
        $asWebp = $asWebp && ! $overwrite;

        if ($media->mime_type === ImageProcessor::GIF_MIME) {
            throw new UnprocessableImageException('Animated GIFs cannot be processed.');
        }

        $disk = Storage::disk($media->disk);

        if (! $disk->exists($media->path)) {
            throw new InvalidImageException('The source file no longer exists on disk.');
        }

        $sourcePath = $disk->path($media->path);
        $sourceMime = $this->processor->detectMime($sourcePath);

        $outputMime = $asWebp ? 'image/webp' : $sourceMime;
        $extension = $asWebp ? 'webp' : $media->extension;
        $directory = dirname($media->path);
        $newPath = $overwrite ? $media->path : $directory.'/'.Str::uuid().'.'.$extension;

        $this->processor->process($sourcePath, $disk->path($newPath), $sourceMime, [
            'crop' => $options['crop'] ?? null,
            'maxWidth' => $options['maxWidth'] ?? config('media.default_max_width'),
            'quality' => $options['quality'] ?? config('media.webp_quality'),
            'asWebp' => $asWebp,
        ]);

        if (! $overwrite && $newPath !== $media->path) {
            $disk->delete($media->path);
        }

        $dimensions = $this->processor->dimensions($disk->path($newPath));

        $media->update([
            'file_name' => basename($newPath),
            'path' => $newPath,
            'mime_type' => $outputMime,
            'extension' => $extension,
            'size' => $disk->size($newPath),
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
        ]);

        return $media->refresh();
    }

    /**
     * Remove the physical file behind a media record.
     */
    public function deleteFile(Media $media): void
    {
        if (blank($media->disk) || blank($media->path)) {
            return;
        }

        $disk = Storage::disk($media->disk);

        if ($disk->exists($media->path)) {
            $disk->delete($media->path);
        }
    }

    private function extensionForMime(string $mime): string
    {
        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/avif' => 'avif',
            default => 'bin',
        };
    }
}
