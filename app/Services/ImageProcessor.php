<?php

namespace App\Services;

use App\Exceptions\InvalidImageException;
use App\Exceptions\UnprocessableImageException;
use GdImage;
use RuntimeException;

/**
 * Pure GD image processing: no database or Storage access.
 */
class ImageProcessor
{
    public const GIF_MIME = 'image/gif';

    /**
     * Detect the real mime type of a file and verify it is an actual image.
     */
    public function detectMime(string $path): string
    {
        if (! is_file($path)) {
            throw new InvalidImageException('The image file could not be found.');
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);

        if (! in_array($mime, config('media.allowed_mimes'), true)) {
            throw new InvalidImageException("Unsupported image type: {$mime}");
        }

        // Reject text files (or any non-image content) renamed with an image extension.
        if (getimagesize($path) === false) {
            throw new InvalidImageException('The file content is not a valid image.');
        }

        return $mime;
    }

    /**
     * @return array{width: int, height: int}
     */
    public function dimensions(string $path): array
    {
        $info = @getimagesize($path);

        if ($info === false) {
            throw new InvalidImageException('Unable to read image dimensions.');
        }

        return ['width' => $info[0], 'height' => $info[1]];
    }

    /**
     * Detect animated GIFs by parsing the file structure (GD can only decode the first frame).
     */
    public function isAnimatedGif(string $path): bool
    {
        $handle = @fopen($path, 'rb');

        if ($handle === false) {
            return false;
        }

        try {
            $header = fread($handle, 6);

            if (! in_array($header, ['GIF87a', 'GIF89a'], true)) {
                return false;
            }

            fseek($handle, 10, SEEK_SET);
            $packed = ord((string) fread($handle, 1));

            if ($packed & 0x80) {
                $globalColorTableBytes = 3 * (2 << ($packed & 0x07));
                fseek($handle, $globalColorTableBytes, SEEK_CUR);
            }

            $frames = 0;

            while (! feof($handle)) {
                $block = fread($handle, 1);

                if ($block === false || $block === '') {
                    break;
                }

                $blockId = ord($block);

                if ($blockId === 0x3B) { // Trailer
                    break;
                }

                if ($blockId === 0x2C) { // Image descriptor
                    $frames++;

                    fseek($handle, 8, SEEK_CUR);
                    $imagePacked = ord((string) fread($handle, 1));

                    if ($imagePacked & 0x80) {
                        $localColorTableBytes = 3 * (2 << ($imagePacked & 0x07));
                        fseek($handle, $localColorTableBytes, SEEK_CUR);
                    }

                    fseek($handle, 1, SEEK_CUR); // LZW minimum code size
                    $this->skipSubBlocks($handle);
                } elseif ($blockId === 0x21) { // Extension
                    fseek($handle, 1, SEEK_CUR); // Extension label
                    $this->skipSubBlocks($handle);
                }
            }

            return $frames > 1;
        } finally {
            fclose($handle);
        }
    }

    /**
     * Process an image from source to destination.
     *
     * @param  array{crop?: array{x: int, y: int, width: int, height: int}, maxWidth?: int, quality?: int, asWebp?: bool}  $options
     */
    public function process(string $src, string $dest, string $mime, array $options = []): void
    {
        if ($mime === self::GIF_MIME) {
            throw new UnprocessableImageException('Animated GIFs cannot be processed.');
        }

        $crop = $options['crop'] ?? null;
        $maxWidth = (int) ($options['maxWidth'] ?? config('media.default_max_width'));
        $quality = (int) ($options['quality'] ?? config('media.webp_quality'));
        $asWebp = (bool) ($options['asWebp'] ?? false);

        $source = $this->createFromMime($src, $mime);
        $image = $source['image'];

        try {
            if (is_array($crop) && count($crop) === 4) {
                $image = $this->crop($image, $crop['x'], $crop['y'], $crop['width'], $crop['height'], $mime);
            }

            $width = imagesx($image);
            $height = imagesy($image);

            if ($maxWidth > 0 && $width > $maxWidth) {
                $ratio = $maxWidth / $width;
                $image = $this->resize($image, (int) round($width * $ratio), (int) round($height * $ratio), $mime);
            }

            $outputMime = $asWebp ? 'image/webp' : $mime;

            $this->encode($image, $dest, $outputMime, $quality);
        } finally {
            imagedestroy($image);
        }
    }

    /**
     * @return array{image: GdImage}
     */
    private function createFromMime(string $src, string $mime): array
    {
        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($src),
            'image/png' => @imagecreatefrompng($src),
            'image/webp' => @imagecreatefromwebp($src),
            'image/avif' => function_exists('imagecreatefromavif') ? @imagecreatefromavif($src) : false,
            default => false,
        };

        if (! $image instanceof GdImage) {
            throw new UnprocessableImageException("The image format ({$mime}) cannot be processed.");
        }

        return ['image' => $image];
    }

    private function crop(GdImage $image, int $x, int $y, int $width, int $height, string $mime): GdImage
    {
        $cropped = imagecrop($image, [
            'x' => max($x, 0),
            'y' => max($y, 0),
            'width' => max($width, 1),
            'height' => max($height, 1),
        ]);

        if (! $cropped instanceof GdImage) {
            throw new RuntimeException('Unable to crop the image.');
        }

        $this->preserveAlpha($cropped, $mime);
        imagedestroy($image);

        return $cropped;
    }

    private function resize(GdImage $image, int $width, int $height, string $mime): GdImage
    {
        $resized = imagecreatetruecolor($width, $height);

        if (! $resized instanceof GdImage) {
            throw new RuntimeException('Unable to allocate memory for the resized image.');
        }

        $this->preserveAlpha($resized, $mime);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        imagedestroy($image);

        return $resized;
    }

    private function preserveAlpha(GdImage $image, string $mime): void
    {
        if (in_array($mime, ['image/png', 'image/webp'], true)) {
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);

            if ($transparent !== false) {
                imagefill($image, 0, 0, $transparent);
            }
        }
    }

    private function encode(GdImage $image, string $dest, string $mime, int $quality): void
    {
        $directory = dirname($dest);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $result = match ($mime) {
            'image/jpeg' => imagejpeg($image, $dest, $quality),
            'image/png' => imagepng($image, $dest, 9),
            'image/webp' => imagewebp($image, $dest, $quality),
            'image/avif' => function_exists('imageavif') ? imageavif($image, $dest, $quality) : false,
            default => false,
        };

        if ($result === false) {
            throw new RuntimeException("Unable to encode the processed image to: {$dest}");
        }
    }

    private function skipSubBlocks($handle): void
    {
        while (! feof($handle)) {
            $size = ord((string) fread($handle, 1));

            if ($size === 0) {
                break;
            }

            fseek($handle, $size, SEEK_CUR);
        }
    }
}
