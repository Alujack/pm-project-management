FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Set working directory
WORKDIR /app

# Copy app files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Expose port
EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080

