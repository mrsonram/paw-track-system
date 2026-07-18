# PawTrack — PHP-FPM image (served by the nginx service via fastcgi)
# ใช้ PHP 8.0 ให้ตรงกับที่ Laravel 8.40 + composer.lock รองรับ (prophecy ต้องการ php <8.1)
# PHP 8.1 ทำให้ Laravel 8 แปลง deprecation เป็น fatal ระหว่างโหลด class → boot ไม่ขึ้น
FROM php:8.0-fpm

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
# ติดตั้ง dev deps ด้วย เพราะ bootstrap/cache/packages.php อ้าง provider ของ
#   dev packages (ignition, sail, collision, crud-generator)
# --no-scripts: ข้าม artisan package:discover ที่ต้องมีแอปครบ + .env
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-interaction --prefer-dist

# Copy the rest of the application and rebuild the optimized autoloader
COPY . .
RUN composer dump-autoload --optimize --no-scripts

# php-fpm listens on 9000 (consumed by the nginx service, not published to host)
EXPOSE 9000
CMD ["php-fpm"]
