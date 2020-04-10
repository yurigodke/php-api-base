FROM php:7.2-apache

COPY php.ini /usr/local/etc/php/

RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
