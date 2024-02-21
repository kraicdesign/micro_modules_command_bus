ARG DOCKER_PHP_VERSION=8.2

FROM php:${DOCKER_PHP_VERSION}-fpm-alpine

ARG DOCKER_UID=1000
ARG DOCKER_GID=1000

# Fix timezone (only required for testing to stop php -v and php-fpm -v from complaining to stderr)
RUN set -eux \
    && echo "date.timezone=UTC" > /usr/local/etc/php/php.ini

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
# Fetch the latest apk manifests
# Update existing packages
# Cleanup after ourselves to keep this layer as small as possible
# Details: https://wiki.alpinelinux.org/wiki/Alpine_Linux_package_management
RUN set -eux \
    && apk update \
    && apk upgrade \
    && chmod +x /usr/local/bin/install-php-extensions \
    && apk add --no-cache bash procps git unzip

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_CACHE_DIR /.composer_cache
ENV COMPOSER_HOME /.composer

RUN set -eux \
    && install-php-extensions @composer

# php extensions
RUN install-php-extensions xdebug ast pcov
