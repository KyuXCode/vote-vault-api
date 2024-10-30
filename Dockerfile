FROM php:8.3-apache

RUN apt-get update
RUN apt-get install -y zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get install -y postgresql libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install

EXPOSE 80

ENTRYPOINT php artisan migrate && apache2-foreground && php artisan route:list

