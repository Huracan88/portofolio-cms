<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_es' => 'Desarrollo Backend', 'name_en' => 'Backend Development', 'sort_order' => 1],
            ['name_es' => 'Desarrollo Frontend', 'name_en' => 'Frontend Development', 'sort_order' => 2],
            ['name_es' => 'DevOps & Infraestructura', 'name_en' => 'DevOps & Infrastructure', 'sort_order' => 3],
            ['name_es' => 'Carrera & Habilidades Blandas', 'name_en' => 'Career & Soft Skills', 'sort_order' => 4],
            ['name_es' => 'Tutoriales & Guías', 'name_en' => 'Tutorials & Guides', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name_en'])],
                array_merge($cat, ['slug' => Str::slug($cat['name_en'])])
            );
        }
    }
}
