# Utilise une image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copier les fichiers de l’application dans le dossier web d’Apache
COPY . /var/www/html/

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html

# Activer le mod_rewrite d’Apache si nécessaire
RUN a2enmod rewrite

# Afficher les erreurs PHP dans Apache
RUN echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/errors.ini

# Expose le port Apache
EXPOSE 80

# Le conteneur démarre avec Apache
CMD ["apache2-foreground"]