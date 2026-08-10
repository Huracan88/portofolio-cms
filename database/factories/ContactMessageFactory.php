<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    private static array $messages = [
        [
            'name' => 'María García',
            'email' => 'maria.garcia@example.com',
            'subject' => 'Propuesta de colaboración freelance',
            'message' => 'Hola, vi tu portafolio y me interesa contratarte para un proyecto de desarrollo web con Laravel. ¿Podríamos agendar una llamada para discutir los detalles?',
            'is_read' => false,
            'replied_at' => null,
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
            'replied_at' => null,
        ],
    ];

    private static int $messageIndex = 0;

    public function definition(): array
    {
        $msg = self::$messages[self::$messageIndex % count(self::$messages)];
        self::$messageIndex++;

        $msg['is_read'] = false;
        $msg['replied_at'] = null;

        return $msg;
    }
}
