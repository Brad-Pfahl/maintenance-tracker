FROM php:8.3-apache

# Install PHP dependencies
RUN apt-get update && apt-get install -y \
     git unzip curl zip libicu-dev libzip-dev libpng-dev libjpeg-dev \
     libfreetype6-dev libonig-dev libxml2-dev mariadb-client libpq-dev \
     && docker-php-ext-install pdo pdo_pgsql pgsql intl zip opcache


# Enable Apache rewrite module
RUN a2enmod rewrite

# Ensure Apache obeys .htaccess files
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Set working dir OUTSIDE web root
WORKDIR /app

# Copy Symfony project (everything)
COPY . /app

# Symlink public to Apache root
RUN rm -rf /var/www/html && ln -s /app/public /var/www/html

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create var directory and fix permissions
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var

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
