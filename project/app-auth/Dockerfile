FROM php:8.3-fpm-alpine

RUN apk add --no-cache git \
        libpq-dev \
        autoconf \
        bash \
        build-base

RUN docker-php-ext-install pdo pdo_pgsql
RUN pecl install redis && docker-php-ext-enable redis

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

COPY ./app /var/www
WORKDIR /var/www

EXPOSE 8002

CMD ["php", "-S", "0.0.0.0:8002", "-t", "/var/www", "/var/www/public/index.php"]