# syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.5 \
    OS_VARIANT=noble

###################
# Base PHP target #
###################
FROM milejko/php:${PHP_VERSION}-${OS_VARIANT} AS base

######################
# Test runner target #
######################
FROM base AS test-runner

ENV XDEBUG_ENABLE=1 \
    XDEBUG_MODE=coverage \
    OPCACHE_VALIDATE_TIMESTAMPS=1

# debian & alpine have different paths for apcu.ini
RUN set -eux; \
    echo "apc.enable_cli=1" >> /etc/php/${PHP_VERSION}/mods-available/apcu.ini || \
    echo "apc.enable_cli=1" >> /etc/php${PHP_VERSION/./}/conf.d/apcu.ini
