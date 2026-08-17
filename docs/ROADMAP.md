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
- [x] Scaffold Laravel 13.24 + dependencias (Livewire 4.3, Filament 5.7, Spatie Permission, Pest 4, Pint, Larastan)
- [x] `.env` apuntando a MariaDB `porta_cms` en Docker; migración base OK (`migrate:fresh --seed`)
- [x] Sitio verificado (`php artisan serve` → home 200, panel `/admin` 200); vincular sitio en Herd cuando se desee

### Fase 1 — Modelo de datos y auth (cerrada)
- [x] Migraciones + modelos: `Skill`, `Experience`, `Project`, `Post`, `Category`, `Tag`, `ContactMessage`, `User` (roles admin/editor)
- [x] Spatie Permission: roles `admin` y `editor`; Shield en Filament; policies por recurso
- [x] Factories + seeders demo (re-ejecutables) con contenido bilingüe `_es`/`_en`
- [x] Panel Filament: login, dashboard, gestión de usuarios/roles
- [x] Criterios: `migrate:fresh --seed` OK; acceso admin funciona; tests de auth/roles verdes (32 tests, Pint, Larastan)

### Fase 2 — Sistema de diseño (designer + developer) (cerrada)
- [x] Tokens Tailwind v4 en `resources/css/app.css` (`@theme`): paleta primary/accent/neutral/success/danger, tipografías (Space Grotesk + Instrument Sans vía Bunny Fonts), radios, sombras
- [x] Tema oscuro profesional + acento + modo claro; tipografía display y de cuerpo
- [x] Componentes base Blade/Tailwind/Alpine: x-button, x-card, x-badge, x-heading, x-section, x-input, x-textarea, x-select, x-svg-icon
- [x] i18n EN/ES: `lang/en.json`, `lang/es.json` (~129 claves), selector de idioma en layout (middleware SetLocale, session+cookie+`?lang=`)
- [x] Criterios: home renderiza con el sistema de diseño; contraste/focus OK; sin strings quemadas

### Fase 3 — Portafolio público (developer) (cerrada)
- [x] Layout principal + navegación SPA (`wire:navigate`)
- [x] Home: hero (retrato IA), about/bio, skills grid, experiencia (timeline), proyectos destacados, educación, idiomas
- [x] Páginas: Proyectos (grid + filtros sector + detalle), Blog (listado + artículo), Contacto (formulario + honeypot + persistencia)
- [x] SEO: x-seo (OG, canonical, hreflang), sitemap.xml, robots.txt, lazy loading
- [x] Contenido real del CV del usuario en seeders (Profile, 23 skills, 2 experiencias, 2 educación, 2 idiomas, 10 proyectos) + 6 imágenes generadas con nano-banana
- [x] Criterios: rutas públicas funcionales (200); tests de páginas verdes (75 tests); PHPStan 0 errores; QA reviewer APROBADO

### Fase 4 — CMS (Filament v5)
- [ ] Recursos Filament: Projects, Posts (categorías/tags), Skills, Experiences, ContactMessages (bandeja), Users
- [ ] Dashboard con estadísticas (proyectos, posts, mensajes sin leer)
- [x] Media manager: tabla `media`, upload (GD), crop Cropper.js + presets, WebP/compresión, papelera, copy URL, picker en Posts/Projects (QA APROBADO)
- [x] Settings + IA en el panel: settings key-value propios (`settings`), key de OpenRouter cifrada (Crypt), página Settings (admin/super_admin), traducción IA ES↔EN en Post/Project (admin+editor), generación de imágenes IA en Media Library (colección `generated`) vía OpenRouter
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
- Contenido real del portafolio (foto, bio, proyectos, CV) → cargado vía seeders en Fase 3; fotografía real y URLs de redes sociales (GitHub/LinkedIn) pendientes de aportar por el usuario (se reemplaza la foto IA y se llenan los enlaces vía CMS).
- Roles: `admin` (todo) y `editor` (publicar contenido, no gestionar usuarios).
