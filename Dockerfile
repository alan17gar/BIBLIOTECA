FROM php:8.2-apache
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html/
EXPOSE 80
