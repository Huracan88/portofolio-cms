---
paths:
  - 'tests/Feature/**'
---

# Feature

## Admin role alone cannot access the Filament panel (Shield)
Filament panel access is gated by Shield's canAccessPanel: a user needs the `super_admin` role (or `panel_user`) to open /admin at all. The `admin` role alone returns 403 on every admin route. In tests, assign both roles (`assignRole('admin', 'super_admin')`) when asserting admin can render a resource page; editor (no roles) still gets 403.
