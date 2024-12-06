FROM php:8.3-fpm-alpine

RUN apk add --no-cache git \
        libpq-dev \
        autoconf \
        bash \
        build-base

RUN pecl install apcu && docker-php-ext-enable apcu

RUN docker-php-ext-install pdo pdo_pgsql opcache

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

COPY ./application /var/www
COPY www.conf /usr/local/etc/php-fpm.d/

RUN echo "apc.enable_cli=1" >> /usr/local/etc/php/php.ini
RUN echo "apc.enable=1" >> /usr/local/etc/php/php.ini

WORKDIR /var/www