FROM php:7-cli-alpine

RUN apk add --no-cache --update --virtual buildDeps g++ make autoconf composer

RUN pecl install redis xdebug && \
    docker-php-ext-enable redis xdebug
