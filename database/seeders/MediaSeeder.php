<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use GdImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $this->seedGradient('media/seed-gradient-cyan.jpg', 1600, 900, [22, 211, 227], [8, 69, 190], 'covers', 'Portada degradado cian', 'Cyan gradient cover', $admin?->id);
        $this->seedGradient('media/seed-gradient-magenta.jpg', 1200, 900, [236, 64, 122], [106, 17, 203], 'general', 'Fondo degradado magenta', 'Magenta gradient background', $admin?->id);
        $this->seedPattern('media/seed-pattern-grid.png', 800, 800, 'general', 'Patrón de cuadrícula', 'Grid pattern', $admin?->id, function (GdImage $image, int $width, int $height): void {
            $color = imagecolorallocate($image, 255, 255, 255);

            if ($color !== false) {
                for ($x = 0; $x <= $width; $x += 40) {
                    imageline($image, $x, 0, $x, $height, $color);
                }

                for ($y = 0; $y <= $height; $y += 40) {
                    imageline($image, 0, $y, $width, $y, $color);
                }
            }
        });
        $this->seedGradient('media/seed-gradient-amber.webp', 1920, 1080, [251, 191, 36], [217, 70, 239], 'covers', 'Portada degradado ámbar', 'Amber gradient cover', $admin?->id, 'webp');
        $this->seedPattern('media/seed-pattern-dots.png', 1024, 768, 'general', 'Patrón de puntos', 'Dots pattern', $admin?->id, function (GdImage $image, int $width, int $height): void {
            $color = imagecolorallocate($image, 255, 255, 255);

            if ($color !== false) {
                for ($x = 30; $x < $width; $x += 60) {
                    for ($y = 30; $y < $height; $y += 60) {
                        imagefilledellipse($image, $x, $y, 14, 14, $color);
                    }
                }
            }
        });
    }

    /**
     * @param  array{0: int, 1: int, 2: int}  $from
     * @param  array{0: int, 1: int, 2: int}  $to
     */
    private function seedGradient(
        string $path,
        int $width,
        int $height,
        array $from,
        array $to,
        string $collection,
        string $altEs,
        string $altEn,
        ?int $userId,
        string $format = 'jpg',
    ): void {
        $this->seedImage($path, $width, $height, $collection, $altEs, $altEn, $userId, $format, function (GdImage $image, int $width, int $height) use ($from, $to): void {
            for ($y = 0; $y < $height; $y++) {
                $ratio = $y / max($height - 1, 1);
                $color = imagecolorallocate(
                    $image,
                    (int) round($from[0] + ($to[0] - $from[0]) * $ratio),
                    (int) round($from[1] + ($to[1] - $from[1]) * $ratio),
                    (int) round($from[2] + ($to[2] - $from[2]) * $ratio),
                );

                if ($color !== false) {
                    imageline($image, 0, $y, $width, $y, $color);
                }
            }
        });
    }

    /**
     * @param  callable(GdImage, int, int): void  $draw
     */
    private function seedPattern(
        string $path,
        int $width,
        int $height,
        string $collection,
        string $altEs,
        string $altEn,
        ?int $userId,
        callable $draw,
    ): void {
        $this->seedImage($path, $width, $height, $collection, $altEs, $altEn, $userId, 'png', $draw);
    }

    /**
     * @param  callable(GdImage, int, int): void  $draw
     */
    private function seedImage(
        string $path,
        int $width,
        int $height,
        string $collection,
        string $altEs,
        string $altEn,
        ?int $userId,
        string $format,
        callable $draw,
    ): void {
        if (Media::where('path', $path)->exists()) {
            return;
        }

        $image = imagecreatetruecolor($width, $height);

        if (! $image instanceof GdImage) {
            return;
        }

        $draw($image, $width, $height);

        $tmp = tempnam(sys_get_temp_dir(), 'media_');
        $encoded = match ($format) {
            'jpg' => imagejpeg($image, $tmp ?: null, 90),
            'webp' => imagewebp($image, $tmp ?: null, 90),
            default => imagepng($image, $tmp ?: null, 9),
        };

        imagedestroy($image);

        if (! $tmp || ! $encoded) {
            return;
        }

        $diskName = (string) config('media.disk');
        $disk = Storage::disk($diskName);
        $disk->put($path, file_get_contents($tmp));
        unlink($tmp);

        Media::create([
            'user_id' => $userId,
            'original_name' => basename($path),
            'file_name' => basename($path),
            'disk' => $diskName,
            'path' => $path,
            'mime_type' => $this->mimeForFormat($format),
            'extension' => $format,
            'size' => $disk->size($path),
            'width' => $width,
            'height' => $height,
            'alt_es' => $altEs,
            'alt_en' => $altEn,
            'collection' => $collection,
        ]);
    }

    private function mimeForFormat(string $format): string
    {
        return match ($format) {
            'jpg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => 'image/png',
        };
    }
}
