FROM alpine:latest as storage
WORKDIR /storage
COPY . .

FROM composer:latest as composer
WORKDIR /app
COPY --from=storage /storage/app .
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader --apcu-autoloader --no-scripts \
    && \
    composer dump-env prod

FROM node:alpine as node
WORKDIR /app
COPY --from=composer /app .
RUN yarn install
RUN mkdir -p public \
    && \
    yarn encore production

FROM php:8.0-fpm
# Download script to install PHP extensions and dependencies
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync
RUN DEBIAN_FRONTEND=noninteractive apt-get update -q \
    && \
    DEBIAN_FRONTEND=noninteractive apt-get install -qq -y \
        curl \
        git \
        zip unzip \
    && \
    install-php-extensions \
        bcmath \
        bz2 \
        calendar \
        exif \
        gd \
        intl \
        ldap \
        memcached \
        opcache \
        pdo_pgsql \
        pgsql \
        redis \
        soap \
        xsl \
        zip \
        sockets
RUN sed -i 's/memory_limit = .*/memory_limit = 512M/' "$PHP_INI_DIR/php.ini-production"
RUN sed -i 's/realpath_cache_size = .*/realpath_cache_size = 4M/' "$PHP_INI_DIR/php.ini-production"
RUN sed -i 's/realpath_cache_ttl = .*/realpath_cache_ttl = 600/' "$PHP_INI_DIR/php.ini-production"
RUN sed -i 's/max_execution_time = .*/max_execution_time = 1000/' "$PHP_INI_DIR/php.ini-production"
RUN sed -i 's/max_input_time = .*/max_input_time = 1000/' "$PHP_INI_DIR/php.ini-production"
RUN ln -sf "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/conf.d/php.ini"
COPY --from=storage /storage/docker/php/prod/php.ini /usr/local/etc/php/conf.d/xx-php-ext-custom.ini
WORKDIR /var/www/html
COPY --from=node /app .
RUN php bin/console cache:warmup
RUN chown -R www-data:www-data /var/www/html \
    && \
    chmod -R 755 /var/www/html
RUN php bin/console assets:install