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
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git curl unzip \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libpq-dev \
        pkg-config \
        autoconf make g++; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" pdo pdo_pgsql mbstring zip gd; \
    apt-get clean; rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite and set DocumentRoot to /public
RUN a2enmod rewrite && \
    sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Optimize Composer layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-ansi --optimize-autoloader

# Copy application code
COPY . .

# Copy built frontend from Stage 1 (Vite outputs to public/build)
COPY --from=frontend /app/public/build ./public/build

# Permissions for Laravel writable dirs
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Expose Apache
EXPOSE 80

# Default command
CMD ["apache2-foreground"]
