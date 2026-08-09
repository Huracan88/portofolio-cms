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

| Agente | Rol | Modelo (OpenRouter) |
| --- | --- | --- |
| `planner` | Descompone tareas, arquitectura, DoD | `qwen/qwen3.6-plus` |
| `designer` | Sistema de diseño Tailwind v4, UI pública, i18n | `qwen/qwen3.6-plus` |
| `developer` | Implementa código | `deepseek/deepseek-v4-pro` |
| `reviewer` | QA gate: audita, corre tests, aprueba | `moonshotai/kimi-k2.6` |

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
- `php artisan test` — suite Pest (añadir tests de funcionalidad nueva)
- `vendor/bin/phpstan analyse --memory-limit=512M` — si larastan está instalado
- `npm run build` — si cambian assets (Tailwind/Vite)
- `php artisan migrate` y seeders re-ejecutables (`migrate:fresh --seed` sin errores)

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
