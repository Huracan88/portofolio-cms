# ROADMAP — Portafolio + CMS

Ruta de desarrollo por fases. **Estado**: este documento es la fuente de verdad; se marca el progreso con `[x]` al cerrar cada fase (tras el APROBADO del revisor y la demo al usuario).

## Visión

Portafolio profesional bilingüe (EN/ES) de desarrollador fullstack senior + CMS propio para gestionar proyectos, artículos, skills, experiencia y mensajes de contacto. Desarrollado en local (Herd + MariaDB Docker), con panel admin Filament v5.

## Fases

### Fase 0 — Fundación (en curso)
- [x] `opencode.json` con modelos OpenRouter balanceados
- [x] Subagentes: `planner`, `designer`, `developer`, `reviewer`
- [x] `AGENTS.md` (reglas de proceso) y este `ROADMAP.md`
- [x] Repo git local inicializado
- [x] Scaffold Laravel 13 + dependencias (Livewire 4, Volt, Filament v5, Spatie Permission, Pest, Pint, Larastan)
- [x] `.env` apuntando a MariaDB `porta_cms` en Docker; migración base OK
- [ ] Sitio servido por Herd y accesible localmente (`php artisan serve` o sitio Herd)

### Fase 1 — Modelo de datos y auth
- [ ] Migraciones + modelos: `Skill`, `Experience`, `Project`, `Post`, `Category`, `Tag`, `ContactMessage`, `User` (roles admin/editor)
- [ ] Spatie Permission: roles `admin` y `editor`; Shield en Filament; policies por recurso
- [ ] Factories + seeders demo (re-ejecutables) con contenido bilingüe `_es`/`_en`
- [ ] Panel Filament: login, dashboard, gestión de usuarios/roles
- [ ] Criterios: `migrate:fresh --seed` OK; acceso admin funciona; tests de auth/roles verdes

### Fase 2 — Sistema de diseño (designer + developer)
- [ ] Tokens Tailwind v4 en `resources/css/app.css` (`@theme`): paleta, tipografías, radios, sombras
- [ ] Tema oscuro profesional + acento + modo claro; tipografía display y de cuerpo
- [ ] Componentes base Blade/Tailwind/Alpine: botones, cards, badges, nav, footer, headings, formulario
- [ ] i18n EN/ES: `lang/en.json`, `lang/es.json`, selector de idioma en layout
- [ ] Criterios: home renderiza con el sistema de diseño; contraste/focus OK; sin strings quemadas

### Fase 3 — Portafolio público (developer)
- [ ] Layout principal + navegación SPA (`wire:navigate`)
- [ ] Home: hero, about/bio, skills, experiencia (timeline), proyectos destacados
- [ ] Páginas: Proyectos (grid + detalle), Blog (listado + artículo), Contacto (formulario + validación)
- [ ] SEO: metadatos, Open Graph, sitemap, feeds; lazy loading de imágenes
- [ ] Criterios: rutas públicas funcionales con datos demo; tests de páginas verdes; rendimiento razonable

### Fase 4 — CMS (Filament v5)
- [ ] Recursos Filament: Projects, Posts (categorías/tags), Skills, Experiences, ContactMessages (bandeja), Users
- [ ] Dashboard con estadísticas (proyectos, posts, mensajes sin leer)
- [ ] Upload de imágenes/media (thumbnail, portadas de proyectos, avatares)
- [ ] Roles/permisos aplicados: `admin` gestiona todo; `editor` publica contenido
- [ ] Criterios: CRUD completo desde el panel; permisos respetados; tests de recursos verdes

### Fase 5 — QA final (reviewer)
- [ ] Audit completa: seguridad, rendimiento, accesibilidad, responsive
- [ ] Suite Pest ampliada (unit/feature/componentes Livewire + Filament)
- [ ] Pint + Larastan en verde; `npm run build` sin warnings
- [ ] Fixes y refinamiento de UX
- [ ] Criterios: QA gate global APROBADO; demo final al usuario

### Fase 6 — Preparación de despliegue (futuro, a definir)
- [ ] Configuración producción (build, migraciones, cache, HTTPS)
- [ ] Decidir proveedor (Forge/VPS) y dominio
- [ ] Backups y monitorización

## Supuestos abiertos (confirmar con el usuario)

- Proveedor de despliegue final y dominio → se decide en Fase 6.
- Contenido real del portafolio (foto, bio, proyectos, CV) → se cargará vía CMS en demo.
- Roles: `admin` (todo) y `editor` (publicar contenido, no gestionar usuarios).
