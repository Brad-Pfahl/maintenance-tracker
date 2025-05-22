# Base image
FROM php:8.3-apache

# Required PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libpq-dev libzip-dev libonig-dev libxml2-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libxslt-dev mariadb-client \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache

# Enable Apache modules
RUN a2enmod rewrite

# Tell Apache to serve from /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Replace default Apache config to point to the correct root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html
# Copy project files
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build frontend assets
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npx encore production

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
