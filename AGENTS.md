# AGENTS.md — Reglas de proceso del proyecto

Proyecto: **Portafolio + CMS** de desarrollador fullstack senior (10+ años, stack PHP/Laravel).

## Stack (versiones verificadas, julio 2026)

- **Laravel 13** (PHP 8.3+) — servido localmente por **Laravel Herd**
- **Livewire 4** (componentes single-file/Volt) + **Alpine.js**
- **Filament v5** — panel de administración (CMS) sobre Livewire
- **Tailwind CSS v4** (CSS-first con `@theme`, sin tailwind.config.js) + **Vite 7**
- **MariaDB en Docker** — `127.0.0.1:3306`, user `porta_cms`, pass `porta_cms`, db `porta_cms`
- **Spatie Laravel Permission** + **Filament Shield** — roles y permisos
- **Pest 4** (tests) + **Laravel Pint** (formato) + **Larastan** (análisis estático)

## Equipo de agentes

| Agente | Rol | Modelo (Zen) |
| --- | --- | --- |
| `planner` | Descompone tareas, arquitectura, DoD | `opencode-go/kimi-k3` |
| `designer` | Sistema de diseño Tailwind v4, UI pública, i18n | `opencode-go/hy3` |
| `developer` | Implementa código | `opencode-go/deepseek-v4-flash` |
| `reviewer` | QA gate: audita, corre tests, aprueba | `opencode-go/glm-5.2` |

## Flujo de trabajo por tarea

1. **Planear** → el orquestador/`planner` define la tarea con su DoD.
2. **Diseñar** (si es UI) → `designer` propone estructura/tokens, confirma, luego implementa.
3. **Desarrollar** → `developer` implementa y ejecuta su verificación local.
4. **Revisar** → `reviewer` audita, corre tests y emite APROBADO / REQUIERE CAMBIOS.
5. **Cerrar** → el orquestador reporta al usuario y pasa a la siguiente tarea.

Reglas de paso:
- Una tarea NO se da por cerrada sin el APROBADO del revisor.
- El revisor NO edita archivos (solo reporta); las correcciones las hace el developer.
- Si una tarea toca UI, el designer define primero y el developer implementa (o el designer implementa si el orquestador lo indica).

## Verificación obligatoria (quality gate)

Siempre que haya cambios de código PHP/CSS/JS, ejecutar y dejar en verde:

- `vendor/bin/pint` — formato de código
- `vendor/bin/filacheck` — deprecaciones y patrones obsoletos de Filament v5 (complementa a PHPStan)
- `php artisan test` — suite Pest (añadir tests de funcionalidad nueva)
- `vendor/bin/phpstan analyse --memory-limit=512M` — si larastan está instalado
- `npm run build` — si cambian assets (Tailwind/Vite)
- `php artisan migrate` y seeders re-ejecutables (`migrate:fresh --seed` sin errores)

## Herramientas IA disponibles (Laravel Boost)

- El MCP server `laravel-boost` está registrado en `opencode.json` (ejecuta `artisan boost:mcp`). Proporciona herramientas útiles: **Search Docs** (docs semánticas de Laravel 13/Filament 5/Livewire 4/Pest/Tailwind), **Database Schema**, **Get Absolute URL**, **Record Rule**, etc. Úsalas cuando una API sea desconocida o dudosa (p. ej. APIs de Filament 5) en lugar de adivinar.
- Skills de Boost publicadas en `.agents/skills/` (cargadas vía `skills.paths` de opencode): `infer-conventions`, `laravel-best-practices`, `livewire-development`, `pest-testing`, `tailwindcss-development`. Activar la skill relevante al trabajar en su dominio.
- Los guidelines de Boost están incrustados abajo en este archivo (sección `<laravel-boost-guidelines>`). Si se corre `boost:update`, re-verificar que no sobrescriba este archivo ni mueva los skills.

## Trampas conocidas (lecciones aprendidas — leer antes de verificar)

- **Entorno Windows/PowerShell**: el `php` del PATH es 7.4 (inservible para Laravel 13). Usar SIEMPRE `& "C:\Users\Andres\.config\herd\bin\php83\php.exe"`.
- **Captura de salida**: en esta shell, ejecuciones directas de phpstan/pest/pint pueden devolver salida vacía. Capturar siempre con `| Out-String` (PowerShell) o redirigir `> archivo.txt 2>&1` y leerlo. El resultado real llega como JSON de herramienta: `{"tool":"phpstan","result":"passed","errors":0}` / `{"tool":"pest","result":"passed",...}`.
- **PHPStan y `reportUnmatched`**: NUNCA añadir `reportUnmatched: false` a `phpstan.neon.dist`. Rompe la salida del wrapper de opencode (produce 0 bytes, imposible verificar) y oculta patrones `ignoreErrors` obsoletos. El patrón correcto es: config con level 5 + larastan, `ignoreErrors` SOLO para false positives reales (verificado: si un patrón no matchea, PHPStan lo reporta como error; no silenciarlo).
- **Aislar config vs. código**: si un análisis da salida vacía o dudosa, correr PHPStan desde `C:\Users\Andres\AppData\Local\Temp\opencode` (fuera del proyecto, sin config) sobre un archivo/scope concreto con `--error-format=json`. Si ahí produce salida, el problema es la config del proyecto, no el código.
- Verificación oficial del gate PHPStan: `& "C:\Users\Andres\.config\herd\bin\php83\php.exe" vendor\bin\phpstan analyse --memory-limit=512M --no-progress --error-format=json | Out-String` → esperar `"result":"passed"` y EXIT=0.

## Convenciones de código

- **Idiomas**: código, variables, commits → inglés. Documentación y textos de salida de agentes → español.
- **Laravel 13**: configuración en `bootstrap/app.php`; controllers delgados; lógica de validación en Form Requests; servicios para lógica de negocio; Eloquent sobre queries crudas; policies para autorización.
- **Livewire 4**: componentes single-file (Volt) para la vista pública; `wire:navigate` para navegación SPA; no usar jQuery.
- **Filament 5**: recursos en `app/Filament/`; validación en el recurso; autorización con policies + Spatie permissions; proteger TODA acción de escritura (nadie sin login).
- **Tailwind v4**: tokens y tema en `resources/css/app.css` (`@theme`); utilidades; nada de CSS custom salvo tokens/animaciones.
- **i18n**: textos visibles en `lang/es.json` y `lang/en.json` con `__()`; selector de idioma en el layout; contenido bilingüe con columnas `_es`/`_en`.
- **BD**: MariaDB; migraciones con Schema facade; factories + seeders para demo; nunca escribir en BD fuera de migrations/seeders/panel.
- **Sin comentarios de relleno**; solo los necesarios.
- **No commitear** a menos que el usuario lo pida explícitamente.

## Recursos de referencia

- `docs/ROADMAP.md` — ruta de desarrollo por fases (el estado vive aquí; actualizarlo al cerrar fases).
- `AGENTS.md` — este archivo (reglas).
- Modelos/agentes configurados en `opencode.json` y `.opencode/agent/*.md`.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.3. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd at `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs. Never run commands to serve the site. It is always available.
- Use the `herd` CLI to manage services, PHP versions, and sites (e.g. `herd sites`, `herd services:start <service>`, `herd php:list`). Run `herd list` to discover all available commands.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

</laravel-boost-guidelines>
