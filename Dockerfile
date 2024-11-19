# syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.3

###########################################
# Distribution target                     #
###########################################
FROM milejko/php:${PHP_VERSION}-cli AS dist

ENV OPCACHE_VALIDATE_TIMESTAMPS=0

COPY . .

RUN composer install --no-dev

###########################################
# Test runner target                      #
###########################################
FROM dist AS test-runner

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage

RUN composer install --dev

###########################################
# Dev server target                       #
###########################################
FROM dist AS dev-server

ENV OPCACHE_VALIDATE_TIMESTAMPS=1

EXPOSE 8080

WORKDIR /var/www/html/public

CMD [ "php", "-S", "0.0.0.0:8080" ]