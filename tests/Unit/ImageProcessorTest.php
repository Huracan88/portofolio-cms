<?php

use App\Exceptions\InvalidImageException;
use App\Exceptions\UnprocessableImageException;
use App\Services\ImageProcessor;

function makeTestImage(string $path, int $width, int $height, string $format = 'jpg', ?callable $draw = null): void
{
    $image = imagecreatetruecolor($width, $height);
    $background = imagecolorallocate($image, 200, 30, 30);

    if ($background !== false) {
        imagefill($image, 0, 0, $background);
    }

    if ($draw) {
        $draw($image, $width, $height);
    }

    match ($format) {
        'jpg' => imagejpeg($image, $path, 90),
        'png' => imagepng($image, $path, 9),
        'webp' => imagewebp($image, $path, 90),
        'gif' => imagegif($image, $path),
        'avif' => imageavif($image, $path, 80),
    };

    imagedestroy($image);
}

function makeAnimatedGif(string $path, int $frames = 2): void
{
    $width = 10;
    $height = 10;

    $content = 'GIF89a';
    $content .= pack('vvvv', $width, $height, 0x80, 0); // screen descriptor with global color table
    $content .= "\x00"; // aspect ratio
    $content .= str_repeat("\x00\x00\x00", 2); // 2-entry global color table

    for ($i = 0; $i < $frames; $i++) {
        $content .= "\x21\xF9\x04\x00\x0A\x00\x00\x00"; // graphic control extension
        $content .= "\x2C".pack('vvvv', 0, 0, $width, $height)."\x00"; // image descriptor
        $content .= "\x02\x02\x00\x01\x00"; // LZW min code size + one sub-block + terminator
    }

    $content .= "\x3B"; // trailer

    file_put_contents($path, $content);
}

beforeEach(function () {
    $this->processor = new ImageProcessor;
    $this->fixturesDir = storage_path('framework/testing/media-fixtures');

    if (! is_dir($this->fixturesDir)) {
        mkdir($this->fixturesDir, 0755, true);
    }
});

afterEach(function () {
    foreach (glob($this->fixturesDir.'/*') ?: [] as $file) {
        @unlink($file);
    }

    @rmdir($this->fixturesDir);
});

it('detects the real mime type of images', function (string $format, string $expected) {
    $file = $this->fixturesDir.'/image.'.$format;
    makeTestImage($file, 20, 20, $format);

    expect($this->processor->detectMime($file))->toBe($expected);
})->with([
    'jpeg' => ['jpg', 'image/jpeg'],
    'png' => ['png', 'image/png'],
    'webp' => ['webp', 'image/webp'],
    'gif' => ['gif', 'image/gif'],
]);

it('rejects a text file renamed with an image extension', function () {
    $file = $this->fixturesDir.'/fake.jpg';
    file_put_contents($file, 'this is not an image');

    expect(fn () => $this->processor->detectMime($file))
        ->toThrow(InvalidImageException::class);
});

it('reads image dimensions', function () {
    $file = $this->fixturesDir.'/image.jpg';
    makeTestImage($file, 320, 240);

    expect($this->processor->dimensions($file))->toBe(['width' => 320, 'height' => 240]);
});

it('detects animated gifs', function () {
    $animated = $this->fixturesDir.'/animated.gif';
    makeAnimatedGif($animated, 3);

    $single = $this->fixturesDir.'/single.gif';
    makeTestImage($single, 20, 20, 'gif');

    expect($this->processor->isAnimatedGif($animated))->toBeTrue();
    expect($this->processor->isAnimatedGif($single))->toBeFalse();
});

it('crops an image to exact dimensions', function () {
    $source = $this->fixturesDir.'/source.jpg';
    $dest = $this->fixturesDir.'/cropped.jpg';
    makeTestImage($source, 400, 300);

    $this->processor->process($source, $dest, 'image/jpeg', [
        'crop' => ['x' => 50, 'y' => 40, 'width' => 200, 'height' => 150],
        'maxWidth' => 2000,
        'asWebp' => false,
    ]);

    expect($this->processor->dimensions($dest))->toBe(['width' => 200, 'height' => 150]);
});

it('resizes respecting the maximum width', function () {
    $source = $this->fixturesDir.'/source.jpg';
    $dest = $this->fixturesDir.'/resized.jpg';
    makeTestImage($source, 1600, 1200);

    $this->processor->process($source, $dest, 'image/jpeg', [
        'maxWidth' => 800,
        'asWebp' => false,
    ]);

    expect($this->processor->dimensions($dest))->toBe(['width' => 800, 'height' => 600]);
});

it('does not upscale images smaller than the maximum width', function () {
    $source = $this->fixturesDir.'/source.jpg';
    $dest = $this->fixturesDir.'/unchanged.jpg';
    makeTestImage($source, 400, 300);

    $this->processor->process($source, $dest, 'image/jpeg', [
        'maxWidth' => 2400,
        'asWebp' => false,
    ]);

    expect($this->processor->dimensions($dest))->toBe(['width' => 400, 'height' => 300]);
});

it('encodes to webp with the requested quality', function () {
    $source = $this->fixturesDir.'/source.jpg';
    $dest = $this->fixturesDir.'/output.webp';
    makeTestImage($source, 300, 200);

    $this->processor->process($source, $dest, 'image/jpeg', [
        'asWebp' => true,
        'quality' => 60,
    ]);

    expect((new finfo(FILEINFO_MIME_TYPE))->file($dest))->toBe('image/webp');
    expect($this->processor->dimensions($dest))->toBe(['width' => 300, 'height' => 200]);
});

it('preserves png alpha when processing to webp', function () {
    $source = $this->fixturesDir.'/transparent.png';
    $dest = $this->fixturesDir.'/transparent.webp';
    makeTestImage($source, 100, 100, 'png', function (GdImage $image, int $width, int $height): void {
        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);

        if ($transparent !== false) {
            imagefilledrectangle($image, 0, 0, 49, 49, $transparent);
        }
    });

    $this->processor->process($source, $dest, 'image/png', ['asWebp' => true]);

    $decoded = imagecreatefromwebp($dest);
    expect($decoded)->not->toBeFalse();

    $alpha = imagecolorat($decoded, 10, 10) >> 24;
    imagedestroy($decoded);

    expect($alpha)->toBeGreaterThan(100);
});

it('rejects animated gifs', function () {
    $source = $this->fixturesDir.'/animated.gif';
    $dest = $this->fixturesDir.'/output.jpg';
    makeAnimatedGif($source);

    expect(fn () => $this->processor->process($source, $dest, 'image/gif', []))
        ->toThrow(UnprocessableImageException::class);
});

it('rejects avif processing when the extension is unavailable', function () {
    if (function_exists('imagecreatefromavif')) {
        $this->markTestSkipped('AVIF support is enabled in this environment.');
    }

    $source = $this->fixturesDir.'/source.avif';
    $dest = $this->fixturesDir.'/output.jpg';
    makeTestImage($source, 20, 20, 'avif');

    expect(fn () => $this->processor->process($source, $dest, 'image/avif', []))
        ->toThrow(UnprocessableImageException::class);
});
