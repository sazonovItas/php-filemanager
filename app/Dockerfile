FROM php:8.3-fpm-alpine3.19

RUN docker-php-ext-install pdo pdo_mysql

# allow composer using from superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

# copy composer binaries
COPY --from=composer:lts /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# install project dependencies
RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

# TODO: Need to know for what this
RUN composer dump-autoload --optimize
