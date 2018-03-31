FROM php:7-fpm

WORKDIR /var/www
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

ADD . /var/www
RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:50:/' /etc/passwd
RUN chown -R www-data:www-data /var/www
RUN chmod 755 /var/www