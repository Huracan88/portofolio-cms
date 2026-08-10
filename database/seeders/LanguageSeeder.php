<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'language_es' => 'Español',
                'language_en' => 'Spanish',
                'proficiency_es' => 'Nativo',
                'proficiency_en' => 'Native',
                'sort_order' => 1,
            ],
            [
                'language_es' => 'Inglés',
                'language_en' => 'English',
                'proficiency_es' => 'Competencia profesional',
                'proficiency_en' => 'Professional working proficiency',
                'sort_order' => 2,
            ],
        ];

        foreach ($languages as $lang) {
            Language::firstOrCreate(
                ['language_en' => $lang['language_en']],
                $lang
            );
        }
    }
}
