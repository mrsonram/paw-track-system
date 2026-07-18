# PawTrack — PHP-FPM image (served by the nginx service via fastcgi)
FROM php:8.1-fpm

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies first (better layer caching)
# --no-scripts skips artisan package:discover which needs a full app + .env
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-interaction --prefer-dist

# Copy the rest of the application and rebuild the optimized autoloader
COPY . .
RUN composer dump-autoload --optimize --no-scripts

# php-fpm listens on 9000 (consumed by the nginx service, not published to host)
EXPOSE 9000
CMD ["php-fpm"]
