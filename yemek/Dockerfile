FROM php:7.4-apache

RUN apt-get update && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html/

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . .