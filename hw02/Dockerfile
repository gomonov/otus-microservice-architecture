FROM php:8.3-fpm-alpine

COPY . /var/www
WORKDIR /var/www
EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www", "/var/www/index.php"]