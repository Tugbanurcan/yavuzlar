# Temel imaj olarak PHP ve Apache kullan
FROM php:7.4-apache

# Gerekli PHP uzantılarını yükle
RUN docker-php-ext-install pdo pdo_mysql

# Uygulama dosyalarını kopyala
COPY . /var/www/html/

# Apache'yi başlat
CMD ["apache2-foreground"]
