FROM php:8.2-apache

# Habilitar el módulo de reescritura de Apache (necesario para el archivo .htaccess)
RUN a2enmod rewrite

# Dar permisos correctos para que Apache pueda leer la raíz del proyecto sin dar error 403
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copiar todo el proyecto a la carpeta del servidor
COPY . /var/www/html/

EXPOSE 80
