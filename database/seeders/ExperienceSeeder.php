<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $experiences = [
            [
                'company_es' => 'Secretaría de Salud (SESA)',
                'company_en' => 'Ministry of Health (SESA)',
                'position_es' => 'Programador',
                'position_en' => 'Programmer',
                'description_es' => 'Diseño, desarrollo, mantenimiento y administración de sistemas de información institucionales; modelado, optimización y mantenimiento de bases de datos relacionales críticas; soporte técnico especializado y consultoría de procesos administrativos internos.',
                'description_en' => 'Design, development, maintenance, and administration of institutional information systems; modeling, optimization, and maintenance of critical relational databases; specialized technical support and consulting for internal administrative processes.',
                'started_at' => '2008-09-01',
                'ended_at' => null,
                'is_current' => true,
                'sort_order' => 1,
            ],
            [
                'company_es' => 'Profesional Autónomo / Consultor de Software',
                'company_en' => 'Independent Professional / Software Consultant',
                'position_es' => 'Senior Fullstack Developer & Consultor',
                'position_en' => 'Senior Fullstack Developer & Consultant',
                'description_es' => 'Ingeniería de procesos empresariales y arquitectura de software a la medida; administración, hardening y configuración de servidores de producción Linux; desarrollo end-to-end de aplicaciones web, móviles e integraciones de pago/facturación.',
                'description_en' => 'Business process engineering and custom software architecture; administration, hardening, and configuration of Linux production servers; end-to-end development of web and mobile applications with payment/invoicing integrations.',
                'started_at' => '2014-09-01',
                'ended_at' => null,
                'is_current' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::firstOrCreate(
                ['company_en' => $exp['company_en'], 'position_en' => $exp['position_en']],
                $exp
            );
        }
    }
}
