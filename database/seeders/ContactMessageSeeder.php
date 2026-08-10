<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'name' => 'María García',
                'email' => 'maria.garcia@example.com',
                'subject' => 'Propuesta de colaboración freelance',
                'message' => 'Hola, vi tu portafolio y me interesa contratarte para un proyecto de desarrollo web con Laravel. ¿Podríamos agendar una llamada para discutir los detalles?',
                'is_read' => false,
            ],
            [
                'name' => 'John Smith',
                'email' => 'john.smith@techcorp.com',
                'subject' => 'Job opportunity - Senior Laravel Developer',
                'message' => 'We are looking for a senior Laravel developer to join our team at TechCorp. Your portfolio looks exactly like what we need. Would you be interested in discussing this opportunity?',
                'is_read' => true,
                'replied_at' => '2025-07-01 14:30:00',
            ],
            [
                'name' => 'Carlos Mendoza',
                'email' => 'carlos@startupxyz.io',
                'subject' => 'Consulta técnica sobre Filament',
                'message' => 'Hola, estoy empezando con Filament y tengo algunas dudas sobre la integración con Spatie Permission. Vi tu artículo sobre el tema y me gustaría pedirte orientación si es posible.',
                'is_read' => false,
            ],
        ];

        foreach ($messages as $msg) {
            ContactMessage::firstOrCreate(
                ['email' => $msg['email'], 'subject' => $msg['subject']],
                $msg
            );
        }
    }
}
