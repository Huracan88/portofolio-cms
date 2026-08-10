<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    private static array $experiences = [
        [
            'company_es' => 'TechCorp Solutions',
            'company_en' => 'TechCorp Solutions',
            'position_es' => 'Desarrollador Fullstack Senior',
            'position_en' => 'Senior Fullstack Developer',
            'description_es' => 'Lideré el desarrollo de una plataforma SaaS multi-tenant con Laravel y Vue.js, gestionando un equipo de 5 desarrolladores. Implementé CI/CD con GitHub Actions y Docker, resultando en despliegues diarios sin fricción.',
            'description_en' => 'Led the development of a multi-tenant SaaS platform with Laravel and Vue.js, managing a team of 5 developers. Implemented CI/CD with GitHub Actions and Docker, resulting in frictionless daily deployments.',
            'started_at' => '2022-03-01',
            'ended_at' => null,
            'is_current' => true,
            'sort_order' => 1,
        ],
        [
            'company_es' => 'Digital Agency Pro',
            'company_en' => 'Digital Agency Pro',
            'position_es' => 'Desarrollador Backend PHP',
            'position_en' => 'PHP Backend Developer',
            'description_es' => 'Desarrollé APIs RESTful y microservicios con Laravel para clientes del sector e-commerce. Optimicé consultas de base de datos que redujeron tiempos de respuesta en un 40%.',
            'description_en' => 'Developed RESTful APIs and microservices with Laravel for e-commerce clients. Optimized database queries reducing response times by 40%.',
            'started_at' => '2019-06-01',
            'ended_at' => '2022-02-28',
            'is_current' => false,
            'sort_order' => 2,
        ],
        [
            'company_es' => 'StartupLab Inc.',
            'company_en' => 'StartupLab Inc.',
            'position_es' => 'Desarrollador Fullstack Junior',
            'position_en' => 'Junior Fullstack Developer',
            'description_es' => 'Participé en el desarrollo de productos MVP con Laravel y jQuery. Colaboré en la migración de una aplicación legacy de PHP vanilla a Laravel, mejorando la mantenibilidad del código.',
            'description_en' => 'Participated in MVP product development with Laravel and jQuery. Collaborated on migrating a legacy PHP vanilla application to Laravel, improving code maintainability.',
            'started_at' => '2017-01-15',
            'ended_at' => '2019-05-31',
            'is_current' => false,
            'sort_order' => 3,
        ],
    ];

    private static int $experienceIndex = 0;

    public function definition(): array
    {
        $exp = self::$experiences[self::$experienceIndex % count(self::$experiences)];
        self::$experienceIndex++;

        return $exp;
    }
}
