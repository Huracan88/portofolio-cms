<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Project;
use App\Models\ProjectGalleryImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectGalleryImageFactory extends Factory
{
    protected $model = ProjectGalleryImage::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'media_id' => Media::factory(),
            'caption_es' => fake()->sentence(5),
            'caption_en' => fake()->sentence(5),
            'sort_order' => 0,
        ];
    }
}
