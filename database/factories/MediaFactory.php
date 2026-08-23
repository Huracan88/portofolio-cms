<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'original_name' => fake()->word().'.jpg',
            'file_name' => fake()->word().'.jpg',
            'disk' => 'public',
            'path' => 'media/'.fake()->uuid().'.jpg',
            'mime_type' => 'image/jpeg',
            'extension' => 'jpg',
            'size' => fake()->numberBetween(1000, 500000),
            'width' => fake()->numberBetween(400, 1920),
            'height' => fake()->numberBetween(300, 1080),
            'alt_es' => fake()->sentence(4),
            'alt_en' => fake()->sentence(4),
            'collection' => 'general',
        ];
    }
}
