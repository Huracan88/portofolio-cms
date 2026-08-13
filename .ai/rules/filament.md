---
paths:
  - 'resources/css/filament/**'
---

# Filament

## cropperjs must stay on 1.6.2 and modal tests use assertMountedActionModalSee
cropperjs v2 (2.1.x) ships NO CSS file — `@import 'cropperjs/dist/cropper.css'` fails the build. Pin cropperjs@1.6.2 (classic, includes dist/cropper.css; same Cropper API: getData/setAspectRatio). Also: Filament action modals render their content in the Livewire response `partials`, so tests must assert with `assertMountedActionModalSee()`, not `assertSee()`.
