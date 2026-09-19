FROM dunglas/frankenphp:1-php8.3-alpine AS runner

# 1. Install required PHP extensions for Laravel 13, Filament v5 and ImageProcessor
RUN install-php-extensions \
    pdo_mysql \
    gd \
    intl \
    zip \
    bcmath \
    opcache

# 2. Install Node.js & NPM for Vite asset compilation
RUN apk add --no-cache nodejs npm

# 3. Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV SERVER_NAME="http://:80"
ENV CADDY_GLOBAL_OPTIONS="auto_https off"

WORKDIR /app

# 4. Copy dependency manifests
COPY composer.json composer.lock package.json package-lock.json ./

# 5. Install PHP dependencies with full PHP 8.3 environment
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# 6. Install Node dependencies
RUN npm ci

# 7. Copy entire codebase
COPY . .

# 8. Dump autoloader and build production assets with Vite
RUN composer dump-autoload --optimize --no-dev --no-scripts
RUN npm run build

# 9. Clean up node_modules to keep image lightweight
RUN rm -rf node_modules

# 10. Ensure directories exist and configure permissions
RUN mkdir -p /app/storage/framework/cache/data \
             /app/storage/framework/sessions \
             /app/storage/framework/views \
             /app/storage/app/public \
             /app/bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["frankenphp", "run"]
