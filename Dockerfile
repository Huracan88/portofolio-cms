# Stage 1: Install Composer dependencies (with --ignore-platform-reqs for build stage)
FROM composer:2 AS composer-builder
WORKDIR /app
COPY composer*.json ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev --ignore-platform-reqs

# Stage 2: Build frontend assets with Vite & Tailwind v4
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
# Filament's theme.css imports from vendor/filament/filament
COPY --from=composer-builder /app/vendor /app/vendor
COPY . .
RUN npm run build

# Stage 3: Production PHP runtime with FrankenPHP
FROM dunglas/frankenphp:1-php8.3-alpine AS runner

# Install essential PHP extensions for Laravel 13, Filament v5 and ImageProcessor
RUN install-php-extensions \
    pdo_mysql \
    gd \
    intl \
    zip \
    bcmath \
    opcache

ENV SERVER_NAME=":80"
ENV CADDY_GLOBAL_OPTIONS="auto_https off"

WORKDIR /app

# Copy application files with vendor and compiled assets
COPY --from=composer-builder /app /app
COPY --from=frontend /app/public/build /app/public/build
COPY Caddyfile /etc/caddy/Caddyfile

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
