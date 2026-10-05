# Imagen base con PHP 8.3 y Apache.
FROM php:8.3-apache

# Directorio de trabajo de la aplicación.
WORKDIR /var/www/html

# Instalar dependencias del sistema (Composer las necesita) y activar mod_rewrite.
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Instalador de extensiones PHP (evita el fallo "Cannot find config.m4"
# de docker-php-ext-install en PHP 8.3 y resuelve dependencias solo).
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_sqlite bcmath mbstring xml zip curl

# Ajustar el DocumentRoot de Apache hacia /var/www/html/public (requisito de Laravel).
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar Composer desde su imagen oficial.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar el código del proyecto (el contexto de construcción es la carpeta crudlaravel/).
COPY . .

# Instalar dependencias PHP de producción.
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos de escritura para Laravel y archivo SQLite inicial.
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache \
    && touch database/database.sqlite \
    && chown www-data:www-data database/database.sqlite

# Render expone el puerto 80 por defecto en servicios Docker.
EXPOSE 80

# Al iniciar: ejecutar migraciones y seeders, luego levantar Apache.
CMD php artisan migrate --force && php artisan db:seed --force && apache2-foreground
