FROM php:8.0-fpm

RUN apt-get update && apt-get install -y git zlib1g-dev libzip-dev libpq-dev zip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pgsql pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
