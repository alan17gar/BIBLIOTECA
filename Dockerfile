FROM php:8.2-apache

# 1. Instalar y activar el driver de MySQL (PDO) para solucionar el error de conexión
RUN docker-php-ext-install pdo pdo_mysql

# 2. Habilitar el módulo de reescritura de Apache para las rutas internas
RUN a2enmod rewrite

# 3. Dar permisos totales para que cargue tanto la raíz como la carpeta public (CSS/JS)
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 4. Copiar tu proyecto al servidor
COPY . /var/www/html/

EXPOSE 80
