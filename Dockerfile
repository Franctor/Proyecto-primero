FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl libpng-dev && \
    docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Instalar dependencias PHP (Plates, PHPMailer, DOMPDF)
RUN composer require league/plates phpmailer/phpmailer dompdf/dompdf

# Habilitar mod_rewrite (útil para frameworks tipo MVC)
RUN a2enmod rewrite

EXPOSE 80
