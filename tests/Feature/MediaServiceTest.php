<?php

use App\Exceptions\InvalidImageException;
use App\Exceptions\UnprocessableImageException;
use App\Models\Media;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    $this->service = app(MediaService::class);
    $this->user = User::factory()->create();
});

it('stores a valid image and writes the file to disk', function () {
    $media = $this->service->store(UploadedFile::fake()->image('hero.jpg'), $this->user->id, 'general');

    expect($media)->toBeInstanceOf(Media::class);
    expect($media->mime_type)->toBe('image/jpeg');
    expect($media->extension)->toBe('jpg');
    expect($media->collection)->toBe('general');
    expect($media->user_id)->toBe($this->user->id);
    expect($media->width)->not->toBeNull();
    expect($media->height)->not->toBeNull();
    expect(Storage::disk('public')->exists($media->path))->toBeTrue();
    expect($media->original_name)->toBe('hero.jpg');
});

it('rejects files that exceed the maximum upload size', function () {
    config(['media.max_upload_kb' => 5120]);

    expect(fn () => $this->service->store(UploadedFile::fake()->create('big.jpg', 6000)))
        ->toThrow(InvalidImageException::class);
});

it('rejects files whose content is not a real image', function () {
    expect(fn () => $this->service->store(UploadedFile::fake()->create('fake.jpg', 10)))
        ->toThrow(InvalidImageException::class);
});

it('process overwrite keeps the same path and updates dimensions', function () {
    $media = $this->service->store(UploadedFile::fake()->image('hero.jpg', 400, 300), $this->user->id);

    $updated = $this->service->process($media, [
        'overwrite' => true,
        'crop' => ['x' => 0, 'y' => 0, 'width' => 200, 'height' => 200],
        'maxWidth' => 2000,
        'quality' => 80,
        'asWebp' => false,
    ]);

    expect($updated->path)->toBe($media->path);
    expect($updated->width)->toBe(200);
    expect($updated->height)->toBe(200);
    expect(Storage::disk('public')->exists($media->path))->toBeTrue();
});

it('process save as new changes the path, format and deletes the old file', function () {
    $media = $this->service->store(UploadedFile::fake()->image('hero.jpg', 400, 300), $this->user->id);

    $oldPath = $media->path;

    $updated = $this->service->process($media, [
        'overwrite' => false,
        'maxWidth' => 2400,
        'quality' => 70,
        'asWebp' => true,
    ]);

    expect($updated->path)->not->toBe($oldPath);
    expect($updated->path)->toEndWith('.webp');
    expect($updated->mime_type)->toBe('image/webp');
    expect($updated->extension)->toBe('webp');
    expect(Storage::disk('public')->exists($updated->path))->toBeTrue();
    expect(Storage::disk('public')->exists($oldPath))->toBeFalse();
});

it('ignores webp conversion when overwriting in place', function () {
    $media = $this->service->store(UploadedFile::fake()->image('hero.jpg', 400, 300), $this->user->id);

    $updated = $this->service->process($media, [
        'overwrite' => true,
        'maxWidth' => 2000,
        'quality' => 80,
        'asWebp' => true,
    ]);

    expect($updated->path)->toBe($media->path);
    expect($updated->mime_type)->toBe('image/jpeg');
    expect($updated->extension)->toBe('jpg');
    expect(Storage::disk('public')->exists($updated->path))->toBeTrue();
});

it('deleteFile removes the physical file', function () {
    $media = $this->service->store(UploadedFile::fake()->image('hero.jpg'), $this->user->id);

    $this->service->deleteFile($media);

    expect(Storage::disk('public')->exists($media->path))->toBeFalse();
    expect(Media::find($media->id))->not->toBeNull();
});

it('stores a raw binary image and registers it with real mime and dimensions', function () {
    $image = imagecreatetruecolor(32, 24);
    ob_start();
    imagepng($image);
    $png = (string) ob_get_clean();
    imagedestroy($image);

    $media = $this->service->storeBinary($png, 'image/png', 'ai-generated.png', $this->user->id, 'generated');

    expect($media)->toBeInstanceOf(Media::class);
    expect($media->mime_type)->toBe('image/png');
    expect($media->extension)->toBe('png');
    expect($media->collection)->toBe('generated');
    expect($media->user_id)->toBe($this->user->id);
    expect($media->width)->toBe(32);
    expect($media->height)->toBe(24);
    expect(Storage::disk('public')->exists($media->path))->toBeTrue();
});

it('rejects non-image binary payloads', function () {
    expect(fn () => $this->service->storeBinary('this is not an image', 'text/plain', 'fake.txt', $this->user->id, 'generated'))
        ->toThrow(InvalidImageException::class);

    expect(Media::count())->toBe(0);
});

it('rejects processing animated gifs', function () {
    $path = tempnam(sys_get_temp_dir(), 'gif_').'.gif';

    $image = imagecreatetruecolor(20, 20);
    $color = imagecolorallocate($image, 0, 0, 0);

    if ($color !== false) {
        imagefill($image, 0, 0, $color);
    }

    imagegif($image, $path);
    imagedestroy($image);

    $media = $this->service->store(new UploadedFile($path, 'static.gif', 'image/gif', null, true), $this->user->id);

    expect(fn () => $this->service->process($media, ['asWebp' => true]))
        ->toThrow(UnprocessableImageException::class);

    @unlink($path);
});
