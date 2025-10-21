# Use official PHP + Apache image
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install pdo_mysql

# Enable rewrite module
RUN a2enmod rewrite

# Copy your app into Apache’s root directory
COPY ./www/ /var/www/html

# Optional: set working directory
WORKDIR /var/www/html

# Fix permissions (optional but good practice)
RUN chown -R www-data:www-data /var/www/html

# Expose web port
EXPOSE 80
