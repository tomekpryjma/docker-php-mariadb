FROM php:8.1-apache

RUN apt-get update && apt-get install vim -y

RUN pecl install xdebug \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable xdebug