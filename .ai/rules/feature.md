---
paths:
  - 'tests/Feature/**'
  - tests/Feature/ProjectGalleryResourceTest.php
---

# Feature

## Admin role alone cannot access the Filament panel (Shield)
Filament panel access is gated by Shield's canAccessPanel: a user needs the `super_admin` role (or `panel_user`) to open /admin at all. The `admin` role alone returns 403 on every admin route. In tests, assign both roles (`assignRole('admin', 'super_admin')`) when asserting admin can render a resource page; editor (no roles) still gets 403.

## SetLocale middleware overrides app()->setLocale() in tests
SetLocale middleware (bootstrap/app.php) forces the locale from ?lang= → session → cookie → 'es' on every request, so `app()->setLocale('en')` in a feature test is overridden. To render a page in English in tests, pass `?lang=en` in the URL instead.

## Repeater relationship reorder tests: use set(), not fillForm()
For a Filament Repeater with ->relationship()->orderColumn(), fillForm() with record-{id} keys gets re-sorted by the order column (loadStateFromRelationships re-fills from the DB), so reorder assertions fail. Instead read the initial state via $component->get('data.galleryImages') (keys are record-{id}), then ->set('data.galleryImages', array_reverse($state, true)) before call('save').
