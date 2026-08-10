<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

class EducationFactory extends Factory
{
    protected $model = Education::class;

    private static array $educations = [
        [
            'institution_es' => 'Universidad Interamericana para el Desarrollo (UNID)',
            'institution_en' => 'Universidad Interamericana para el Desarrollo (UNID)',
            'degree_es' => 'Licenciatura en Ingeniería en Sistemas de Información',
            'degree_en' => "Bachelor's Degree in Information Systems Engineering",
            'started_at' => 2006,
            'ended_at' => 2009,
            'license_number' => '8566262',
            'sort_order' => 1,
        ],
        [
            'institution_es' => 'CBTis No. 253',
            'institution_en' => 'CBTis No. 253',
            'degree_es' => 'Técnico en Turismo',
            'degree_en' => 'Tourism Technician',
            'started_at' => 2003,
            'ended_at' => 2006,
            'license_number' => null,
            'sort_order' => 2,
        ],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $edu = self::$educations[self::$index % count(self::$educations)];
        self::$index++;

        return $edu;
    }
}
