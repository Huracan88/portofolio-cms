---
paths:
  - 'resources/views/**'
---

# Frontend Design System — Neo-Brutalist Monochrome (always dark)

El front público usa UN único lenguaje visual, aprobado por el usuario con el diseño de la landing (`resources/views/livewire/pages/home.blade.php`). Todo el front debe mantener coherencia total con ese diseño.

## Tokens (definidos en `resources/css/app.css` en `@theme`)

| Token | Valor | Uso |
| --- | --- | --- |
| `bg-neo-bg` | `#121212` | Fondo de página |
| `bg-neo-panel` | `#1e1e1e` | Cards, paneles |
| `bg-neo-panel-deep` | `#18181b` | Header bars, secciones alternas |
| `text-neo-text` | `#ffffff` | Títulos / texto principal |
| `text-neo-muted` | `#a3a3a3` | Texto secundario |
| `border-neo-line` | `#e5e5e5` | Bordes secundarios |
| `shadow-neo-sm` | `3px 3px 0 0 #e5e5e5` | Sombra pequeña |
| `shadow-neo` | `5px 5px 0 0 #ffffff` | Sombra base |
| `shadow-neo-lg` | `8px 8px 0 0 #ffffff` | Sombra grande |
| `shadow-neo-panel` | `4px 4px 0 0 #e5e5e5` | Paneles de skill |

Tipografías: `font-display-heavy` (Archivo Black — títulos, uppercase), `font-display` (Space Grotesk — títulos secundarios), `font-mono` (labels, tracking-widest, uppercase), `font-pixel` (Press Start 2P — detalles decorativos).

## Patrones SIEMPRE obligatorios

- **Elemento interactivo**: `border-2` + `shadow-neo*` + micro-interacción `hover:-translate-x-1 hover:-translate-y-1` (+ `hover:shadow-neo*` si aplica) y `active:translate-x-0 active:translate-y-0 active:shadow-none`, con `transition-all duration-150`.
- **Eyebrow de sección**: `font-mono text-xs font-bold tracking-[0.3em] text-neo-muted uppercase` con prefijo `// `.
- **Título de sección**: `font-display-heavy uppercase tracking-tight leading-[0.95]`.
- **Labels/líneas pequeñas**: `font-mono text-[10px] font-bold uppercase tracking-widest`.
- **Containers**: secciones `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24`; listados `max-w-4xl`; artículos `max-w-3xl`; formularios `max-w-xl`.
- **Imágenes**: `border-2 border-neo-text`, grayscale en cards (`grayscale hover:grayscale-0`), `loading="lazy"`.
- **Empty states**: `border-2 border-neo-text bg-neo-panel p-10 text-center shadow-neo` con texto `font-mono text-xs font-bold uppercase tracking-widest text-neo-muted`.
- **Decoraciones**: sprites `+`, `×`, `#` en `font-mono text-neo-text/20` posicionados absolutamente; marquee dividers con `animate-marquee`.

## Componentes Blade (ya reescritos a neo — REUTILIZAR, no duplicar markup)

- `x-card` (props: hover, padded) — `border-2 border-neo-text bg-neo-panel`, `shadow-neo-sm`; hover: micro-interacción + `shadow-neo-lg`.
- `x-button` (props: variant primary|secondary|ghost, size sm|md|lg, href, type, target, icon) — primary: `bg-neo-text text-neo-bg border-neo-text shadow-neo`; secondary: `bg-neo-panel text-neo-text border-neo-text shadow-neo-sm`; ghost: `border-neo-line text-neo-muted`. Base mono uppercase tracking-widest.
- `x-section` (props: title, eyebrow, align left|center) — container + eyebrow `//` + `<x-heading level="2">`.
- `x-badge` (props: variant primary|secondary|muted|neutral) — `border-2`, cuadradas (sin rounded), `font-mono text-[10px] uppercase tracking-widest`.
- `x-heading` (props: level 1-3, size xs…hero, weight default heavy).
- `x-input` / `x-textarea` / `x-select` — `border-2 border-neo-line bg-neo-panel text-neo-text placeholder-neo-muted focus:border-neo-text focus:ring-2 focus:ring-neo-text/30`; labels mono.
- `x-pagination` — vista por defecto registrada (`Paginator::defaultView('components.pagination')` + método `paginationView()` en componentes Livewire con `WithPagination`; Livewire 4 sobreescribe el default, sin `paginationView()` la paginación vuelve a Tailwind clásica).

## PROHIBIDO en el front público

- **NUNCA** clases `dark:`/`light:` — el sitio es siempre dark (el layout fuerza `<html class="dark">` y body `bg-neo-bg text-neo-text`).
- **NUNCA** `rounded-full`/`rounded-xl`/`rounded-lg`/`rounded-md` — esquinas cuadradas (solo `rounded-none` si hace falta).
- **NUNCA** `bg-white`, tokens `primary-*`, `accent-*`, `neutral-*`, `success-*`, `danger-*`, ni `shadow-sm|md|lg|xl` del sistema — solo tokens `neo-*`.
- No duplicar estilos inline cuando existe el componente (`x-card`, `x-button`, `x-badge`, etc.).
- No tocar Filament/admin ni los componentes `x-svg-icon`/`x-seo`.

## Referencias de arquitectura

- Layout único: `resources/views/layouts/app.blade.php`. Header/footer: `resources/views/partials/{header,footer}.blade.php`.
- La landing aprobada es la referencia visual de TODO el sistema: `resources/views/livewire/pages/home.blade.php`.
- Rutas del front: `/`, `/projects`, `/projects/{slug}`, `/blog`, `/blog/{slug}`, `/contact` (no existen `/home-2`/`/home-3`, devuelven 404).
