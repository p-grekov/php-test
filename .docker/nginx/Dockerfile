FROM nginx:stable-alpine3.17-slim

COPY .docker/nginx/site.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

COPY --chown=www-data:www-data public public