<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    private static array $tags = [
        ['en' => 'Laravel', 'es' => 'Laravel'],
        ['en' => 'PHP', 'es' => 'PHP'],
        ['en' => 'Vue.js', 'es' => 'Vue.js'],
        ['en' => 'Docker', 'es' => 'Docker'],
        ['en' => 'API Design', 'es' => 'Diseño de APIs'],
        ['en' => 'Testing', 'es' => 'Testing'],
        ['en' => 'Performance', 'es' => 'Rendimiento'],
        ['en' => 'Architecture', 'es' => 'Arquitectura'],
        ['en' => 'Filament', 'es' => 'Filament'],
        ['en' => 'Tailwind CSS', 'es' => 'Tailwind CSS'],
        ['en' => 'Livewire', 'es' => 'Livewire'],
        ['en' => 'MySQL', 'es' => 'MySQL'],
    ];

    private static int $tagIndex = 0;

    public function definition(): array
    {
        $tag = self::$tags[self::$tagIndex % count(self::$tags)];
        self::$tagIndex++;

        return [
            'name_es' => $tag['es'],
            'name_en' => $tag['en'],
            'slug' => Str::slug($tag['en']),
        ];
    }
}
