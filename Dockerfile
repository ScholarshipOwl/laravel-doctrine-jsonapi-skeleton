# syntax=docker/dockerfile:1
FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    icu-dev \
    g++ \
    make \
    autoconf \
    openssl-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions for Laravel
# RUN chown -R www-data:www-data /var/www \
#     && find /var/www -type f -exec chmod 644 {} \; \
#     && find /var/www -type d -exec chmod 755 {} \;

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
