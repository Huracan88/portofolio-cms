<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'full_name' => 'Andrés Adrián Pinto Cámara',
            'title' => 'Fullstack Developer & Software Engineer',
            'email' => 'andrespintocamara@gmail.com',
            'phone' => '+52 983 135 4120',
            'location' => 'Chetumal, Quintana Roo, México',
            'bio_es' => 'Ingeniero en Sistemas de Información con más de 17 años de experiencia continua en diseño, desarrollo, despliegue y mantenimiento de software a medida; especializado en arquitectura web, modernización de sistemas gubernamentales y privados, e integración de APIs complejas. Trayectoria combinando servicio público como Programador en la Secretaría de Salud de Quintana Roo (SESA) con desarrollo independiente fullstack para clientes nacionales e internacionales.',
            'bio_en' => 'Information Systems Engineer with over 17 years of continuous experience in design, development, deployment, and maintenance of custom software; specialized in web architecture, modernization of government and private systems, and complex API integrations. Career combining public service as a Programmer at the Quintana Roo Ministry of Health (SESA) with independent fullstack development for national and international clients.',
            'photo_url' => null,
            'license_number' => '8566262',
            'availability' => 'Freelance / Consultoría / Desarrollo Fullstack',
            'social_links' => ['github' => null, 'linkedin' => null],
        ];
    }
}
