# Gunakan image dasar PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install dependensi sistem yang dibutuhkan
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libonig-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Install Composer dari image resmi Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Salin semua file project ke container
COPY . /var/www/html

# Aktifkan Apache mod_rewrite (wajib untuk Laravel routing)
RUN a2enmod rewrite

# Atur permission agar Laravel bisa berjalan
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Set direktori kerja
WORKDIR /var/www/html

# Install dependensi Laravel, JWT, dan Spatie (optional saat build)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && composer require tymon/jwt-auth \
    && composer require spatie/laravel-permission

# Buka port 80
EXPOSE 80
