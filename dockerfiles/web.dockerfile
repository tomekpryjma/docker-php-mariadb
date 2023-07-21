FROM php:8.2-apache

RUN apt-get update && apt-get install vim -y

RUN docker-php-ext-install pdo_mysql \
    && pecl install xdebug && docker-php-ext-enable xdebug