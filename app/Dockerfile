FROM php:8.3-fpm-alpine3.19

RUN echo $'file_uploads = On    \n\
    memory_limit = 500M         \n\
    upload_max_filesize = 100M  \n\
    post_max_size = 100M        \n\
    max_execution_time = 600    \n\
    max_file_uploads = 50000    \n\
    max_execution_time = 5000   \n\
    max_input_time = 5000       \n\
    ' > /usr/local/etc/php/conf.d/uploads.ini

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
