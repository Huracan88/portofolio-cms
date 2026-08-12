---
paths:
  - 'tests/**'
---

# Tests

## Feature tests render in Spanish (APP_LOCALE=es)
phpunit.xml does NOT override APP_LOCALE, so the .env value (APP_LOCALE=es) applies in tests: every rendered page is Spanish. Write asserts with __() so both locales match, or raw strings that exist in the es output. Also: __('Contact') is not a key in lang/*.json (only "CONTACT"), so it falls back to 'Contact' in both locales — contact page tests rely on that fallback.
