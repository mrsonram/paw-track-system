# Use the official PHP image as a parent image
FROM php:8.1-fpm

# Set the working directory
WORKDIR /var/www/html

# Install dependencies
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
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the existing application directory contents
COPY . /var/www/html

# Copy the existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Change current user to www
USER www-data

# Expose port 8000 and start the built-in PHP server
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
