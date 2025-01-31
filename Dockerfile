FROM php:5.6-apache

COPY ./ /var/www/html/

RUN docker-php-ext-install -j$(nproc) mysqli opcache

EXPOSE 80


