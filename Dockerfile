# syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.3

###########################################
# Base PHP target                         #
###########################################
FROM milejko/php:${PHP_VERSION}-cli AS base

ENV OPCACHE_VALIDATE_TIMESTAMPS=0 \
    MEMORY_LIMIT=128M

###########################################
# Distribution target                     #
###########################################
FROM base AS dist

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
FROM base AS dev-server

ENV OPCACHE_VALIDATE_TIMESTAMPS=1

EXPOSE 8080

CMD [ "php", "-S", "0.0.0.0:8080", "-t", "./public" ]