FROM php:5.6-apache

COPY ./ /var/www/html/

RUN apt-get update && apt-get install -y php5.6-mysql


# Configure your PHP settings if needed
RUN echo "extension=mysql.so" >> /etc/php/5.6/apache2/php.ini


EXPOSE 80


