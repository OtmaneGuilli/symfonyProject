FROM php:8.2-fpm

# Install system packages
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libonig-dev libzip-dev \
    libxml2-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev libxslt1-dev libcurl4-openssl-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
