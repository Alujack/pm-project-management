FROM php:8.2-fpm

# Install required extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev bash \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy custom php.ini
COPY .docker/php/php.ini /usr/local/etc/php/php.ini

# Set working directory
WORKDIR /var/www/html

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
