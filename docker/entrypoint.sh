#!/bin/sh
set -e

# Storage link
php artisan storage:link || true

# Run database migrations
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Run seeders if enabled
if [ "$RUN_SEEDER" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force
fi

# Optimize Laravel for production
php artisan optimize || true
php artisan filament:cache-components || true

exec "$@"
