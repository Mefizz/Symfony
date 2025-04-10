# Use the official PHP image with Apache
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the Symfony project files
COPY . .

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf


# Install Symfony dependencies
RUN composer install

# Set permissions for Symfony
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose the port Apache is running on
EXPOSE 80
