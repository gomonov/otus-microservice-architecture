FROM php:8.3-fpm-alpine

RUN apk add git \
        libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

COPY . /var/www
WORKDIR /var/www

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www", "/var/www/public/index.php"]