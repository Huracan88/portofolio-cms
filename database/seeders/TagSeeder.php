<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name_es' => 'Laravel', 'name_en' => 'Laravel'],
            ['name_es' => 'PHP', 'name_en' => 'PHP'],
            ['name_es' => 'Vue.js', 'name_en' => 'Vue.js'],
            ['name_es' => 'Docker', 'name_en' => 'Docker'],
            ['name_es' => 'Diseño de APIs', 'name_en' => 'API Design'],
            ['name_es' => 'Testing', 'name_en' => 'Testing'],
            ['name_es' => 'Rendimiento', 'name_en' => 'Performance'],
            ['name_es' => 'Arquitectura', 'name_en' => 'Architecture'],
            ['name_es' => 'Filament', 'name_en' => 'Filament'],
            ['name_es' => 'Tailwind CSS', 'name_en' => 'Tailwind CSS'],
            ['name_es' => 'Livewire', 'name_en' => 'Livewire'],
            ['name_es' => 'MySQL', 'name_en' => 'MySQL'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tag['name_en'])],
                array_merge($tag, ['slug' => Str::slug($tag['name_en'])])
            );
        }
    }
}
