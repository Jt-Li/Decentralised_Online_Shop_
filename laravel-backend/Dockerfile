FROM php:7-fpm
RUN apt-get update && apt-get install -y libmcrypt-dev mysql-client \
    && docker-php-ext-install mcrypt pdo_mysql

WORKDIR /var/www

RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:50:/' /etc/passwd