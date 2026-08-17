<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@example.com')->first()
            ?? User::factory()->create(['email' => 'admin@example.com']);

        $categories = Category::all()->keyBy('name_en');
        $tags = Tag::all()->keyBy('name_en');

        $posts = [
            [
                'title_es' => 'Optimización de consultas Eloquent: Guía práctica',
                'title_en' => 'Optimizing Eloquent Queries: A Practical Guide',
                'excerpt_es' => 'Aprende a identificar y resolver problemas de N+1, usar eager loading correctamente y aprovechar los query scopes para mantener tu código limpio y rápido.',
                'excerpt_en' => 'Learn to identify and resolve N+1 problems, use eager loading correctly, and leverage query scopes to keep your code clean and fast.',
                'body_es' => '<p>Uno de los problemas más comunes en aplicaciones Laravel es el rendimiento de las consultas a base de datos. En este artículo te muestro técnicas prácticas que he aplicado en producción.</p><h2>El problema N+1</h2><p>El clásico problema N+1 ocurre cuando iteramos sobre una colección de modelos y accedemos a una relación sin haberla cargado previamente. Laravel ejecutará una consulta adicional por cada modelo.</p><h2>Eager Loading</h2><p>La solución más directa es usar <code>with()</code> para cargar las relaciones de antemano. Pero hay que ser estratégico: cargar relaciones que no necesitas es igual de malo.</p><h2>Query Scopes</h2><p>Los scopes locales te permiten encapsular lógica de filtrado común. Son reutilizables, componibles y mantienen tus controladores limpios.</p>',
                'body_en' => '<p>One of the most common issues in Laravel applications is database query performance. In this article I share practical techniques I\'ve applied in production.</p><h2>The N+1 Problem</h2><p>The classic N+1 problem occurs when we iterate over a collection of models and access a relationship without having pre-loaded it. Laravel will execute an additional query for each model.</p><h2>Eager Loading</h2><p>The most direct solution is to use <code>with()</code> to load relationships upfront. But you need to be strategic: loading relationships you don\'t need is equally bad.</p><h2>Query Scopes</h2><p>Local scopes let you encapsulate common filtering logic. They\'re reusable, composable, and keep your controllers clean.</p>',
                'cover_image_url' => null,
                'is_published' => true,
                'is_featured' => true,
                'published_at' => '2025-07-15 10:00:00',
                'category_en' => 'Backend Development',
                'tags_en' => ['Laravel', 'PHP', 'MySQL', 'Performance'],
            ],
            [
                'title_es' => 'Docker para desarrolladores Laravel: De cero a producción',
                'title_en' => 'Docker for Laravel Developers: From Zero to Production',
                'excerpt_es' => 'Configura tu entorno de desarrollo Laravel con Docker, crea imágenes optimizadas y despliega en producción con confianza.',
                'excerpt_en' => 'Set up your Laravel development environment with Docker, create optimized images, and deploy to production with confidence.',
                'body_es' => '<p>Docker puede parecer intimidante al principio, pero es una herramienta invaluable para cualquier desarrollador Laravel. Vamos paso a paso desde lo básico hasta un entorno productivo.</p><h2>Entorno de desarrollo</h2><p>Usando Laravel Sail o un docker-compose personalizado, puedes tener PHP, MySQL, Redis y todo lo necesario corriendo en segundos.</p><h2>Optimización de imágenes</h2><p>Las imágenes multi-stage te permiten separar las dependencias de build de las de producción, resultando en imágenes mucho más pequeñas y seguras.</p><h2>Despliegue</h2><p>Con un Dockerfile bien construido y docker-compose para producción, puedes desplegar en cualquier VPS, Kubernetes o servicio cloud.</p>',
                'body_en' => '<p>Docker can seem intimidating at first, but it\'s an invaluable tool for any Laravel developer. Let\'s go step by step from basics to a productive environment.</p><h2>Development environment</h2><p>Using Laravel Sail or a custom docker-compose, you can have PHP, MySQL, Redis, and everything you need running in seconds.</p><h2>Image optimization</h2><p>Multi-stage images let you separate build dependencies from production ones, resulting in much smaller and more secure images.</p><h2>Deployment</h2><p>With a well-built Dockerfile and production docker-compose, you can deploy to any VPS, Kubernetes, or cloud service.</p>',
                'cover_image_url' => null,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => '2025-07-01 09:00:00',
                'category_en' => 'DevOps & Infrastructure',
                'tags_en' => ['Docker', 'Laravel', 'PHP'],
            ],
            [
                'title_es' => 'Construyendo paneles admin con Filament: Mejores prácticas',
                'title_en' => 'Building Admin Panels with Filament: Best Practices',
                'excerpt_es' => 'Descubre cómo estructurar tus recursos Filament, crear widgets personalizados y gestionar permisos de forma eficiente con Spatie.',
                'excerpt_en' => 'Discover how to structure your Filament resources, create custom widgets, and manage permissions efficiently with Spatie.',
                'body_es' => '<p>Filament ha revolucionado la forma de construir paneles de administración en Laravel. Aquí comparto las prácticas que he refinado en múltiples proyectos.</p><h2>Estructura de recursos</h2><p>Organiza tus recursos por dominio. Usa grupos de navegación y clusters para paneles grandes.</p><h2>Widgets personalizados</h2><p>Los widgets de Filament son extremadamente flexibles. Puedes crear gráficos, estadísticas en tiempo real y dashboards completamente personalizados.</p><h2>Gestión de permisos</h2><p>Integrar Spatie Permission con Filament Shield te da un control granular sobre quién puede ver y hacer qué en tu panel.</p>',
                'body_en' => '<p>Filament has revolutionized how we build admin panels in Laravel. Here I share practices I\'ve refined across multiple projects.</p><h2>Resource structure</h2><p>Organize your resources by domain. Use navigation groups and clusters for large panels.</p><h2>Custom widgets</h2><p>Filament widgets are extremely flexible. You can create charts, real-time stats, and fully custom dashboards.</p><h2>Permission management</h2><p>Integrating Spatie Permission with Filament Shield gives you granular control over who can see and do what in your panel.</p>',
                'cover_image_url' => null,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => '2025-06-20 11:00:00',
                'category_en' => 'Tutorials & Guides',
                'tags_en' => ['Filament', 'Laravel', 'PHP', 'Tailwind CSS'],
            ],
            [
                'title_es' => 'Testing en Laravel: De novato a experto con Pest',
                'title_en' => 'Testing in Laravel: From Beginner to Expert with Pest',
                'excerpt_es' => 'Guía completa de testing con Pest: tests unitarios, de integración, de APIs y tests de Filament con cobertura realista.',
                'excerpt_en' => 'Complete testing guide with Pest: unit tests, integration tests, API tests, and Filament tests with realistic coverage.',
                'body_es' => '<p>El testing es una inversión que se paga sola. En este artículo cubro el espectro completo de testing en Laravel usando Pest.</p><h2>Tests unitarios</h2><p>Empieza por lo simple: modelos, casts, accessors y relaciones. Son rápidos y te dan confianza en tu dominio.</p><h2>Tests de feature</h2><p>Prueba tus endpoints, middleware, y flujos completos. Usa RefreshDatabase para aislar cada test.</p><h2>Tests de Filament</h2><p>Filament es testeable. Puedes simular acciones del panel, verificar que los permisos funcionan y que los formularios se comportan como esperas.</p>',
                'body_en' => '<p>Testing is an investment that pays for itself. In this article I cover the full spectrum of Laravel testing using Pest.</p><h2>Unit tests</h2><p>Start simple: models, casts, accessors, and relationships. They\'re fast and give you confidence in your domain.</p><h2>Feature tests</h2><p>Test your endpoints, middleware, and complete flows. Use RefreshDatabase to isolate each test.</p><h2>Filament tests</h2><p>Filament is testable. You can simulate panel actions, verify that permissions work, and ensure forms behave as expected.</p>',
                'cover_image_url' => null,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => '2025-05-10 08:00:00',
                'category_en' => 'Backend Development',
                'tags_en' => ['Testing', 'Laravel', 'PHP'],
            ],
            [
                'title_es' => 'Arquitectura hexagonal en Laravel: ¿Vale la pena?',
                'title_en' => 'Hexagonal Architecture in Laravel: Is It Worth It?',
                'excerpt_es' => 'Análisis honesto de arquitectura hexagonal aplicada a proyectos Laravel reales: beneficios, costos y cuándo implementarla.',
                'excerpt_en' => 'Honest analysis of hexagonal architecture applied to real Laravel projects: benefits, costs, and when to implement it.',
                'body_es' => '<p>La arquitectura hexagonal promete código desacoplado y testeable. Pero, ¿tiene sentido en proyectos Laravel del mundo real?</p><h2>Lo bueno</h2><ul><li>Separación clara de dominio e infraestructura</li><li>Tests realmente unitarios (sin base de datos)</li><li>Facilita cambios de proveedores externos</li></ul><h2>Lo malo</h2><ul><li>Más archivos y capas de abstracción</li><li>Curva de aprendizaje para el equipo</li><li>Overkill para proyectos pequeños</li></ul><h2>Mi veredicto</h2><p>Para proyectos grandes con lógica de negocio compleja, sí vale la pena. Para CRUDs y MVPs, quédate con la arquitectura estándar de Laravel.</p>',
                'body_en' => '<p>Hexagonal architecture promises decoupled, testable code. But does it make sense in real-world Laravel projects?</p><h2>The good</h2><ul><li>Clear separation of domain and infrastructure</li><li>Truly unit-testable (no database)</li><li>Easier vendor/provider swaps</li></ul><h2>The bad</h2><ul><li>More files and abstraction layers</li><li>Team learning curve</li><li>Overkill for small projects</li></ul><h2>My verdict</h2><p>For large projects with complex business logic, it\'s worth it. For CRUDs and MVPs, stick with standard Laravel architecture.</p>',
                'cover_image_url' => null,
                'is_published' => false,
                'is_featured' => false,
                'published_at' => null,
                'category_en' => 'Career & Soft Skills',
                'tags_en' => ['Architecture', 'Laravel', 'PHP'],
            ],
        ];

        foreach ($posts as $postData) {
            $categoryEn = $postData['category_en'];
            $tagsEn = $postData['tags_en'];
            unset($postData['category_en'], $postData['tags_en']);

            $postData['slug'] = Str::slug($postData['title_en']);
            $postData['author_id'] = $author->id;
            $postData['category_id'] = $categories[$categoryEn]->id ?? null;

            $post = Post::firstOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );

            $tagIds = Tag::whereIn('name_en', $tagsEn)->pluck('id');
            $post->tags()->sync($tagIds);
        }
    }
}
