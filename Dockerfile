# Stage 1: Build frontend assets
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Production PHP runtime with FrankenPHP
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

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . .
COPY --from=frontend /app/public/build /app/public/build
COPY Caddyfile /etc/caddy/Caddyfile

# Install PHP dependencies without dev packages
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
