FROM php:7.2-apache
COPY php.ini /usr/local/etc/php/
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite
RUN mkdir /var/www/logs
RUN touch /var/www/logs/example.log
RUN chown -R 1000:1000 /var/www/logs
RUN chmod -R 777 /var/www/logs
