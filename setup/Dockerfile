FROM php:8.1-apache as setup

RUN docker-php-ext-install mysqli && a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /app/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN mkdir /app/

WORKDIR /app/

COPY ./ ./

# - - - - - - - - - - - - Super slow for no reason...

# FROM composer:2.4 as install

# ENV COMPOSER_ALLOW_SUPERUSER=1

# COPY ./composer.*/ /app

# RUN composer install

# - - - - - - - - - - - - 

# FROM setup

# COPY --from=install vendor/ vendor/
