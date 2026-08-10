<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    private static array $categories = [
        ['en' => 'Backend Development', 'es' => 'Desarrollo Backend'],
        ['en' => 'Frontend Development', 'es' => 'Desarrollo Frontend'],
        ['en' => 'DevOps & Infrastructure', 'es' => 'DevOps & Infraestructura'],
        ['en' => 'Career & Soft Skills', 'es' => 'Carrera & Habilidades Blandas'],
        ['en' => 'Tutorials & Guides', 'es' => 'Tutoriales & Guías'],
    ];

    private static int $categoryIndex = 0;

    public function definition(): array
    {
        $cat = self::$categories[self::$categoryIndex % count(self::$categories)];
        self::$categoryIndex++;

        return [
            'name_es' => $cat['es'],
            'name_en' => $cat['en'],
            'slug' => Str::slug($cat['en']),
            'sort_order' => $this->faker->unique()->numberBetween(0, 100),
        ];
    }
}
