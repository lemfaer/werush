FROM php:8-fpm-alpine

WORKDIR /var/www/html/

RUN apk update && apk upgrade

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
