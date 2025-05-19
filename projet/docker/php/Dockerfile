FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libonig-dev libzip-dev \
    libxml2-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev libxslt1-dev libcurl4-openssl-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html/

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --no-progress --optimize-autoloader

# Permissions (facultatif mais conseillé)
RUN chown -R www-data:www-data /var/www/html

# Expose port (facultatif selon ton usage)
EXPOSE 9000

CMD ["php-fpm"]
