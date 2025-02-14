FROM php:8.3-fpm-alpine

ENV LIBRDKAFKA_VERSION=v1.8.2

RUN apk add --no-cache git \
        libpq-dev \
        autoconf \
        bash \
        build-base

RUN cd /tmp \
        && git clone \
            --branch ${LIBRDKAFKA_VERSION} \
            --depth 1 \
            https://github.com/edenhill/librdkafka.git \
        && cd librdkafka \
        && ./configure \
        && make \
        && make install

RUN pecl install apcu && docker-php-ext-enable apcu
RUN docker-php-ext-install pdo pdo_pgsql
RUN pecl install rdkafka && docker-php-ext-enable rdkafka
RUN docker-php-ext-configure pcntl --enable-pcntl  && docker-php-ext-install pcntl

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

RUN echo "apc.enable_cli=1" >> /usr/local/etc/php/php.ini
RUN echo "apc.enable=1" >> /usr/local/etc/php/php.ini

COPY ./app /var/www
WORKDIR /var/www

EXPOSE 8004

CMD ["php", "-S", "0.0.0.0:8004", "-t", "/var/www", "/var/www/public/index.php"]