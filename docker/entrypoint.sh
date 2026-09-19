#!/bin/sh
set -e

# Storage link
php artisan storage:link || true

# Run database migrations safely
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "Migration warning (check database connection)"
fi

# Run seeders safely
if [ "$RUN_SEEDER" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force || echo "Seeder warning"
fi

# Optimize Laravel for production
php artisan optimize || true
php artisan filament:cache-components || true

exec "$@"
