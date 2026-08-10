<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    private static array $languages = [
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

    private static int $index = 0;

    public function definition(): array
    {
        $lang = self::$languages[self::$index % count(self::$languages)];
        self::$index++;

        return $lang;
    }
}
