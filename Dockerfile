# Stage 1 - Build Frontend (Vite)
FROM node:18 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./
RUN npm run build

# Stage 2 - Backend (Laravel + PHP + Composer)
FROM php:8.2-apache AS backend

# Install system dependencies and PHP extensions (PostgreSQL for Render)
ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git curl unzip \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libpq-dev \
        pkg-config \
        autoconf make g++; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" pdo pdo_pgsql mbstring zip gd exif; \
    apt-get clean; rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite and set DocumentRoot to /public
RUN a2enmod rewrite && \
    sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Optimize Composer layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-ansi --optimize-autoloader --no-scripts

# Copy application code
COPY . .

# Ensure Laravel runtime directories exist
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache && \
    touch storage/logs/laravel.log

# Run composer scripts now that artisan exists (safe commands)
RUN composer dump-autoload --optimize && php artisan package:discover --ansi || true

# Copy built frontend from Stage 1 (Vite outputs to public/build)
COPY --from=frontend /app/public/build ./public/build

# Permissions for Laravel writable dirs
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Expose Apache
EXPOSE 80

# Default command
CMD ["apache2-foreground"]
