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
                'body_es' => "Uno de los problemas más comunes en aplicaciones Laravel es el rendimiento de las consultas a base de datos. En este artículo te muestro técnicas prácticas que he aplicado en producción.\n\n## El problema N+1\n\nEl clásico problema N+1 ocurre cuando iteramos sobre una colección de modelos y accedemos a una relación sin haberla cargado previamente. Laravel ejecutará una consulta adicional por cada modelo.\n\n## Eager Loading\n\nLa solución más directa es usar `with()` para cargar las relaciones de antemano. Pero hay que ser estratégico: cargar relaciones que no necesitas es igual de malo.\n\n## Query Scopes\n\nLos scopes locales te permiten encapsular lógica de filtrado común. Son reutilizables, componibles y mantienen tus controladores limpios.",
                'body_en' => "One of the most common issues in Laravel applications is database query performance. In this article I share practical techniques I've applied in production.\n\n## The N+1 Problem\n\nThe classic N+1 problem occurs when we iterate over a collection of models and access a relationship without having pre-loaded it. Laravel will execute an additional query for each model.\n\n## Eager Loading\n\nThe most direct solution is to use `with()` to load relationships upfront. But you need to be strategic: loading relationships you don't need is equally bad.\n\n## Query Scopes\n\nLocal scopes let you encapsulate common filtering logic. They're reusable, composable, and keep your controllers clean.",
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
                'body_es' => "Docker puede parecer intimidante al principio, pero es una herramienta invaluable para cualquier desarrollador Laravel. Vamos paso a paso desde lo básico hasta un entorno productivo.\n\n## Entorno de desarrollo\n\nUsando Laravel Sail o un docker-compose personalizado, puedes tener PHP, MySQL, Redis y todo lo necesario corriendo en segundos.\n\n## Optimización de imágenes\n\nLas imágenes multi-stage te permiten separar las dependencias de build de las de producción, resultando en imágenes mucho más pequeñas y seguras.\n\n## Despliegue\n\nCon un Dockerfile bien construido y docker-compose para producción, puedes desplegar en cualquier VPS, Kubernetes o servicio cloud.",
                'body_en' => "Docker can seem intimidating at first, but it's an invaluable tool for any Laravel developer. Let's go step by step from basics to a productive environment.\n\n## Development environment\n\nUsing Laravel Sail or a custom docker-compose, you can have PHP, MySQL, Redis, and everything you need running in seconds.\n\n## Image optimization\n\nMulti-stage images let you separate build dependencies from production ones, resulting in much smaller and more secure images.\n\n## Deployment\n\nWith a well-built Dockerfile and production docker-compose, you can deploy to any VPS, Kubernetes, or cloud service.",
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
                'body_es' => "Filament ha revolucionado la forma de construir paneles de administración en Laravel. Aquí comparto las prácticas que he refinado en múltiples proyectos.\n\n## Estructura de recursos\n\nOrganiza tus recursos por dominio. Usa grupos de navegación y clusters para paneles grandes.\n\n## Widgets personalizados\n\nLos widgets de Filament son extremadamente flexibles. Puedes crear gráficos, estadísticas en tiempo real y dashboards completamente personalizados.\n\n## Gestión de permisos\n\nIntegrar Spatie Permission con Filament Shield te da un control granular sobre quién puede ver y hacer qué en tu panel.",
                'body_en' => "Filament has revolutionized how we build admin panels in Laravel. Here I share practices I've refined across multiple projects.\n\n## Resource structure\n\nOrganize your resources by domain. Use navigation groups and clusters for large panels.\n\n## Custom widgets\n\nFilament widgets are extremely flexible. You can create charts, real-time stats, and fully custom dashboards.\n\n## Permission management\n\nIntegrating Spatie Permission with Filament Shield gives you granular control over who can see and do what in your panel.",
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
                'body_es' => "El testing es una inversión que se paga sola. En este artículo cubro el espectro completo de testing en Laravel usando Pest.\n\n## Tests unitarios\n\nEmpieza por lo simple: modelos, casts, accessors y relaciones. Son rápidos y te dan confianza en tu dominio.\n\n## Tests de feature\n\nPrueba tus endpoints, middleware, y flujos completos. Usa RefreshDatabase para aislar cada test.\n\n## Tests de Filament\n\nFilament es testeable. Puedes simular acciones del panel, verificar que los permisos funcionan y que los formularios se comportan como esperas.",
                'body_en' => "Testing is an investment that pays for itself. In this article I cover the full spectrum of Laravel testing using Pest.\n\n## Unit tests\n\nStart simple: models, casts, accessors, and relationships. They're fast and give you confidence in your domain.\n\n## Feature tests\n\nTest your endpoints, middleware, and complete flows. Use RefreshDatabase to isolate each test.\n\n## Filament tests\n\nFilament is testable. You can simulate panel actions, verify that permissions work, and ensure forms behave as expected.",
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
                'body_es' => "La arquitectura hexagonal promete código desacoplado y testeable. Pero, ¿tiene sentido en proyectos Laravel del mundo real?\n\n## Lo bueno\n\n- Separación clara de dominio e infraestructura\n- Tests realmente unitarios (sin base de datos)\n- Facilita cambios de proveedores externos\n\n## Lo malo\n\n- Más archivos y capas de abstracción\n- Curva de aprendizaje para el equipo\n- Overkill para proyectos pequeños\n\n## Mi veredicto\n\nPara proyectos grandes con lógica de negocio compleja, sí vale la pena. Para CRUDs y MVPs, quédate con la arquitectura estándar de Laravel.",
                'body_en' => "Hexagonal architecture promises decoupled, testable code. But does it make sense in real-world Laravel projects?\n\n## The good\n\n- Clear separation of domain and infrastructure\n- Truly unit-testable (no database)\n- Easier vendor/provider swaps\n\n## The bad\n\n- More files and abstraction layers\n- Team learning curve\n- Overkill for small projects\n\n## My verdict\n\nFor large projects with complex business logic, it's worth it. For CRUDs and MVPs, stick with standard Laravel architecture.",
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
