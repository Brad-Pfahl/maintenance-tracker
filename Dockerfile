# Base image
FROM php:8.3-apache

# Required PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libpq-dev libzip-dev libonig-dev libxml2-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libxslt-dev mariadb-client \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Set the correct document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update Apache site config to use /public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Build frontend assets
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npx encore production

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
