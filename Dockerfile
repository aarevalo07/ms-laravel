# Usamos una imagen oficial de PHP con soporte para FPM
FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones necesarias
# Nota: libpq-dev es necesaria para PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer (el gestor de dependencias de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo dentro del contenedor
WORKDIR /var/www

# Copiar todo el contenido de tu carpeta actual al contenedor
COPY . .

# Instalar dependencias de Laravel
# Usamos --no-scripts para evitar errores si no hay DB conectada aún
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Dar permisos a las carpetas de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto 8000
EXPOSE 8000

# Comando para iniciar el servidor de desarrollo de Laravel
# Host 0.0.0.0 es necesario para que sea accesible desde fuera del contenedor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]