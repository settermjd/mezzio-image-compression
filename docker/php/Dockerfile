# syntax=docker/dockerfile:1
ARG FPM_VERSION=alpine3.17
ARG PHP_VERSION=8.1.18

FROM php:${PHP_VERSION}-fpm-${FPM_VERSION} as base

RUN docker-php-ext-install opcache

FROM base as dev

RUN apk --update-cache add \
    autoconf \
    gcc \
    icu-dev \
    imagemagick-dev \
    jpegoptim \
    jpegtran \
    libjpeg-turbo-utils \
    libpng-dev \
    libzip-dev \
    linux-headers \
    make \
    musl-dev \
    npm \
    optipng \
    pngquant \
    sqlite \
    && pecl install imagick xdebug \
    && docker-php-ext-install gd intl zip \
    && docker-php-ext-enable xdebug intl zip \
    && rm -rf /var/lib/apt/lists/* /var/cache/apk/* \
    && npm -g install svgo

COPY --from=composer:2.2.7 /usr/bin/composer /usr/bin/

WORKDIR /var/www/html

# Make an upload directory for images
RUN mkdir -p data/uploads \
  && chown www-data:www-data data/uploads