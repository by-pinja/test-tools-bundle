FROM php:7.3-alpine

RUN apk add --update --no-cache autoconf make gcc g++ libzip-dev composer \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) zip

WORKDIR /app

COPY . .
RUN composer install
