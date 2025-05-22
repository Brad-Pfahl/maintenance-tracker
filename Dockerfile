# Base image with PHP 8.3 + Apache
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libpq-dev libzip-dev libonig-dev libxml2-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libxslt-dev \
    mariadb-client \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy everything into container
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set permissions
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Build frontend assets
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npx encore production

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
