# Usar una imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema necesarias para Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar extensiones necesarias de PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar el módulo rewrite de Apache (útil para URLs amigables)
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos composer.json y composer.lock
COPY composer.json composer.lock ./

# Instalar las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Copiar el resto de los archivos del proyecto
COPY . /var/www/html/

# Listar los archivos en /var/www/html para depuración
RUN ls -la /var/www/html/

# Asegurar permisos adecuados
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configurar ServerName para evitar el mensaje de advertencia
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Exponer el puerto 80 (usado por Apache)
EXPOSE 80

# Iniciar Apache en modo foreground
CMD ["apache2-foreground"]