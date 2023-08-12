FROM php:8.2-fpm-alpine3.17

ENV UID 1000
ENV GID 1000

ENV PHPIZE_DEPS="$PHPIZE_DEPS linux-headers"
ENV LOCAL_DEPS="shadow"

RUN set -xue ;\
    apk --no-cache add ${PHPIZE_DEPS} ${LOCAL_DEPS} ;\
    docker-php-ext-install pdo_mysql sockets ;\
    usermod -u ${UID} www-data ;\
    groupmod -g ${GID} www-data ;\
    apk --no-cache del ${PHPIZE_DEPS} ;\
    docker-php-source delete


USER www-data:www-data

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install
