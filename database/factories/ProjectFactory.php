<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    private static array $projects = [
        [
            'title_es' => 'Digitalización de Trámites Gubernamentales',
            'title_en' => 'Government Procedures Digitalization',
            'excerpt_es' => 'Sistema integral de digitalización de trámites y procesos administrativos para entidades gubernamentales, reduciendo tiempos de respuesta y eliminando papel.',
            'excerpt_en' => 'Comprehensive system for digitizing administrative procedures and processes for government entities, reducing response times and eliminating paper.',
            'description_es' => "Sistema modular desarrollado para la digitalización de trámites gubernamentales, abarcando desde la captura de solicitudes hasta la emisión de resoluciones oficiales. Incluye:\n\n- Portal ciudadano con autenticación segura\n- Flujos de aprobación multi-nivel configurables\n- Generación automática de oficios y documentos oficiales\n- Integración con sistemas de firma electrónica (FIEL/SAT)\n- Panel de seguimiento en tiempo real para ciudadanos y funcionarios\n- Reportes estadísticos de gestión",
            'description_en' => "Modular system developed for digitizing government procedures, covering from request capture to issuance of official resolutions. Includes:\n\n- Citizen portal with secure authentication\n- Configurable multi-level approval workflows\n- Automatic generation of official documents and memos\n- Integration with electronic signature systems (FIEL/SAT)\n- Real-time tracking panel for citizens and officials\n- Statistical management reports",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'government',
            'is_featured' => false,
            'published_at' => '2024-03-15',
            'sort_order' => 1,
        ],
        [
            'title_es' => 'Sistema de Catastro Municipal',
            'title_en' => 'Municipal Cadastre System',
            'excerpt_es' => 'Plataforma geoespacial para la administración catastral municipal, con mapas interactivos, valuación predial y gestión de contribuyentes.',
            'excerpt_en' => 'Geospatial platform for municipal cadastral administration, with interactive maps, property valuation, and taxpayer management.',
            'description_es' => "Sistema de gestión catastral desarrollado para municipios mexicanos, integrando cartografía digital con administración fiscal. Funcionalidades:\n\n- Mapa catastral interactivo con georreferenciación de predios\n- Motor de valuación conforme a normativa estatal\n- Gestión de cuenta predial y pagos referenciados\n- Emisión de cédulas catastrales digitales\n- Integración con Registro Público de la Propiedad\n- Módulo de atención a contribuyentes con trazabilidad",
            'description_en' => "Cadastral management system developed for Mexican municipalities, integrating digital cartography with tax administration. Features:\n\n- Interactive cadastral map with property georeferencing\n- Valuation engine compliant with state regulations\n- Property account management and referenced payments\n- Digital cadastral certificate issuance\n- Integration with Public Property Registry\n- Taxpayer service module with traceability",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'government',
            'is_featured' => true,
            'published_at' => '2024-06-20',
            'sort_order' => 2,
        ],
        [
            'title_es' => 'Gestión Documental con Firma Electrónica (FIEL/SAT)',
            'title_en' => 'Document Management with Electronic Signature (FIEL/SAT)',
            'excerpt_es' => 'Sistema de gestión documental empresarial con firma electrónica avanzada integrando FIEL del SAT, sello digital y cadena de custodia.',
            'excerpt_en' => 'Enterprise document management system with advanced electronic signature integrating SAT FIEL, digital seal, and chain of custody.',
            'description_es' => "Plataforma de gestión documental con capacidades de firma electrónica utilizando la infraestructura de la FIEL del SAT. Características:\n\n- Firma de documentos PDF con certificados FIEL vigentes\n- Validación de vigencia de certificados contra lista negra SAT\n- Cadena de custodia documental con hash SHA-256\n- Flujos de revisión y aprobación con múltiples firmantes\n- Archivo digital seguro con control de acceso granular\n- Auditoría completa de accesos y modificaciones",
            'description_en' => "Document management platform with electronic signature capabilities using SAT FIEL infrastructure. Features:\n\n- Document signing with valid FIEL certificates\n- Certificate validity check against SAT blacklist\n- Document chain of custody with SHA-256 hashing\n- Review and approval workflows with multiple signers\n- Secure digital archive with granular access control\n- Complete access and modification audit trail",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'government',
            'is_featured' => false,
            'published_at' => '2024-08-10',
            'sort_order' => 3,
        ],
        [
            'title_es' => 'Gestión de Impuestos Estatales (ISBS)',
            'title_en' => 'State Tax Management (ISBS)',
            'excerpt_es' => 'Sistema integral de administración tributaria para impuestos estatales, incluyendo declaraciones, pagos, fiscalización y control de obligaciones.',
            'excerpt_en' => 'Comprehensive tax administration system for state taxes, including declarations, payments, auditing, and obligation control.',
            'description_es' => "Sistema de administración del Impuesto Sobre Bienes y Servicios (ISBS) y otros gravámenes estatales. Módulos:\n\n- Registro de contribuyentes con RFC y datos fiscales\n- Formularios dinámicos de declaración según tipo de contribuyente\n- Cálculo automático de impuestos, recargos y multas\n- Emisión de líneas de captura para pago bancario\n- Módulo de fiscalización con cruce de datos masivos\n- Notificaciones electrónicas y buzón tributario",
            'description_en' => "Administration system for State Goods and Services Tax (ISBS) and other state levies. Modules:\n\n- Taxpayer registry with RFC and fiscal data\n- Dynamic declaration forms by taxpayer type\n- Automatic calculation of taxes, surcharges, and penalties\n- Issuance of payment reference lines for banking\n- Audit module with massive data cross-referencing\n- Electronic notifications and tax mailbox",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'government',
            'is_featured' => false,
            'published_at' => '2024-10-05',
            'sort_order' => 4,
        ],
        [
            'title_es' => 'Emisión de Oficios e Inventario Gubernamental',
            'title_en' => 'Government Memo Issuance and Inventory',
            'excerpt_es' => 'Sistema para la generación de oficios gubernamentales con plantillas dinámicas y control de inventario institucional con trazabilidad completa.',
            'excerpt_en' => 'System for government memo generation with dynamic templates and institutional inventory control with complete traceability.',
            'description_es' => "Aplicación para la emisión controlada de oficios y gestión de inventario en dependencias gubernamentales. Características:\n\n- Plantillas de oficios configurables por tipo de documento\n- Numeración automática con folios oficiales\n- Seguimiento de oficios emitidos con acuse digital\n- Registro de inventario por área, responsable y ubicación\n- Control de altas, bajas y transferencias de bienes\n- Reportes de resguardo patrimonial y conciliación",
            'description_en' => "Application for controlled issuance of memos and inventory management in government dependencies. Features:\n\n- Configurable memo templates by document type\n- Automatic numbering with official folios\n- Emitted memo tracking with digital acknowledgment\n- Inventory registry by area, responsible person, and location\n- Asset additions, removals, and transfers control\n- Asset custody reports and reconciliation",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'government',
            'is_featured' => false,
            'published_at' => '2024-11-15',
            'sort_order' => 5,
        ],
        [
            'title_es' => 'Nómina y Control de Asistencia',
            'title_en' => 'Payroll and Attendance Control',
            'excerpt_es' => 'Sistema empresarial de nómina con cálculo fiscal, timbrado CFDI, portal del empleado y control de asistencia biométrico integrado.',
            'excerpt_en' => 'Enterprise payroll system with tax calculation, CFDI stamping, employee portal, and integrated biometric attendance control.',
            'description_es' => "Plataforma de administración de nómina y recursos humanos para empresas medianas y grandes. Componentes:\n\n- Cálculo de nómina con percepciones, deducciones y retenciones fiscales\n- Timbrado CFDI 4.0 con proveedor PAC autorizado\n- Portal del empleado para consulta de recibos y vacaciones\n- Control de asistencia con integración de relojes biométricos\n- Gestión de incidencias, horas extra y permisos\n- Reportes de nómina, IMSS, INFONAVIT e ISN",
            'description_en' => "Payroll and HR administration platform for medium and large companies. Components:\n\n- Payroll calculation with earnings, deductions, and tax withholding\n- CFDI 4.0 stamping with authorized PAC provider\n- Employee portal for pay stubs and vacation consultation\n- Attendance control with biometric clock integration\n- Incidents, overtime, and leave management\n- Payroll, IMSS, INFONAVIT, and ISN reports",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'enterprise',
            'is_featured' => true,
            'published_at' => '2024-05-01',
            'sort_order' => 6,
        ],
        [
            'title_es' => 'Sistema de Paquetería Internacional',
            'title_en' => 'International Courier System',
            'excerpt_es' => 'Plataforma logística para gestión de envíos internacionales con rastreo en tiempo real, cálculo de tarifas, aduanas y generación de guías.',
            'excerpt_en' => 'Logistics platform for international shipping management with real-time tracking, rate calculation, customs, and label generation.',
            'description_es' => "Sistema integral de logística para empresa de paquetería internacional. Funcionalidades:\n\n- Cotizador de envíos multi-transportista con reglas de negocio\n- Generación de guías aéreas y etiquetas de envío\n- Rastreo de paquetes en tiempo real con webhooks de transportistas\n- Módulo de aduanas con cálculo de impuestos y aranceles\n- Portal de clientes con historial y facturación\n- Dashboard operativo con KPIs de entregas y SLA",
            'description_en' => "Comprehensive logistics system for international courier company. Features:\n\n- Multi-carrier shipping quote calculator with business rules\n- Air waybill and shipping label generation\n- Real-time package tracking with carrier webhooks\n- Customs module with tax and duty calculation\n- Client portal with history and billing\n- Operational dashboard with delivery KPIs and SLA",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'enterprise',
            'is_featured' => true,
            'published_at' => '2024-07-12',
            'sort_order' => 7,
        ],
        [
            'title_es' => 'Administración de Condominios Residenciales',
            'title_en' => 'Residential Condominium Management',
            'excerpt_es' => 'Sistema de administración de condominios con gestión de cuotas, reservación de áreas comunes, comunicación vecinal y reportes financieros.',
            'excerpt_en' => 'Condominium administration system with fee management, common area reservations, neighbor communication, and financial reports.',
            'description_es' => "Plataforma para la administración de conjuntos residenciales. Módulos:\n\n- Catálogo de propietarios e inquilinos por unidad\n- Cálculo y cobro de cuotas de mantenimiento\n- Reservación de áreas comunes con calendario interactivo\n- Registro de ingresos y egresos con conciliación bancaria\n- Tablero de anuncios y comunicación vecinal\n- Emisión de estados de cuenta y reportes financieros\n- Aplicación móvil para residentes con notificaciones push",
            'description_en' => "Platform for residential complex administration. Modules:\n\n- Owner and tenant catalog by unit\n- Maintenance fee calculation and collection\n- Common area reservations with interactive calendar\n- Income and expense registry with bank reconciliation\n- Bulletin board and neighbor communication\n- Account statements and financial reports\n- Mobile app for residents with push notifications",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'enterprise',
            'is_featured' => false,
            'published_at' => '2024-09-18',
            'sort_order' => 8,
        ],
        [
            'title_es' => 'Control de Accesos Portuarios RFID',
            'title_en' => 'Port Access Control RFID',
            'excerpt_es' => 'Sistema de control de accesos vehiculares y peatonales con tecnología RFID para instalaciones portuarias, con registro y monitoreo en tiempo real.',
            'excerpt_en' => 'Vehicle and pedestrian access control system with RFID technology for port facilities, with real-time registration and monitoring.',
            'description_es' => "Solución de control de acceso para recintos portuarios utilizando identificación por radiofrecuencia. Componentes:\n\n- Registro y emisión de tarjetas RFID para personal y vehículos\n- Lectores en puntos de acceso con barreras automatizadas\n- Monitoreo centralizado en tiempo real con mapa del recinto\n- Registro de entradas y salidas con bitácora electrónica\n- Integración con sistema de videovigilancia CCTV\n- Alertas por accesos no autorizados y reportes de aforo",
            'description_en' => "Access control solution for port facilities using radio-frequency identification. Components:\n\n- Registration and issuance of RFID cards for personnel and vehicles\n- Readers at access points with automated barriers\n- Centralized real-time monitoring with facility map\n- Entry and exit logging with electronic logbook\n- Integration with CCTV video surveillance system\n- Unauthorized access alerts and capacity reports",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'enterprise',
            'is_featured' => false,
            'published_at' => '2024-12-01',
            'sort_order' => 9,
        ],
        [
            'title_es' => 'Pasarelas de Pago y Facturación Electrónica (CFDI)',
            'title_en' => 'Payment Gateways and Electronic Invoicing (CFDI)',
            'excerpt_es' => 'Integración de múltiples pasarelas de pago con facturación electrónica CFDI 4.0 automatizada, conciliación bancaria y portal de pagos en línea.',
            'excerpt_en' => 'Multi-payment gateway integration with automated CFDI 4.0 electronic invoicing, bank reconciliation, and online payment portal.',
            'description_es' => "Sistema de pagos y facturación electrónica desarrollado para comercios y prestadores de servicios. Características:\n\n- Integración con pasarelas de pago (Stripe, PayPal, Conekta, Openpay)\n- Generación automática de CFDI 4.0 timbrados tras cada pago\n- Portal de pagos en línea personalizable por comercio\n- Conciliación automática de pagos contra estados de cuenta bancarios\n- Gestión de suscripciones y pagos recurrentes\n- Reportes fiscales y contables con exportación a sistemas ERP",
            'description_en' => "Electronic payment and invoicing system developed for merchants and service providers. Features:\n\n- Integration with payment gateways (Stripe, PayPal, Conekta, Openpay)\n- Automatic generation of stamped CFDI 4.0 after each payment\n- Customizable online payment portal per merchant\n- Automatic reconciliation of payments against bank statements\n- Subscription and recurring payment management\n- Fiscal and accounting reports with export to ERP systems",
            'image_url' => null,
            'project_url' => null,
            'repo_url' => null,
            'sector' => 'fintech',
            'is_featured' => true,
            'published_at' => '2025-01-15',
            'sort_order' => 10,
        ],
    ];

    private static int $projectIndex = 0;

    public function definition(): array
    {
        $proj = self::$projects[self::$projectIndex % count(self::$projects)];
        self::$projectIndex++;

        $proj['slug'] = Str::slug($proj['title_en']);
        $proj['is_visible'] = true;

        return $proj;
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Project $project) {
            $skillIds = Skill::inRandomOrder()->take(fake()->numberBetween(3, 8))->pluck('id');
            $project->skills()->sync($skillIds);
        });
    }
}
