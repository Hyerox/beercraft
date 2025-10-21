FROM php:8.2-apache

# Copie le dossier www dans le dossier web d'Apache
COPY www/ /var/www/html/

# Installe les extensions PHP nécessaires (optionnel)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Active mod_rewrite (utile si tu fais des routes dynamiques)
RUN a2enmod rewrite

# Expose le port 80
EXPOSE 80

CMD ["apache2-foreground"]
