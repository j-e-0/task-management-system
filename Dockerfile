FROM php:8.3-apache

## Update package information
RUN apt-get update

## Configure Apache
RUN a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
    && mv /var/www/html /var/www/public

## Install Composer
RUN curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

###
## PHP Extensisons
###

## Install zip libraries and extension
RUN apt-get install --yes git zlib1g-dev libzip-dev \
    && docker-php-ext-install zip

## Install intl library and extension
RUN apt-get install --yes libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

###
## Optional PHP extensions 
###

## mbstring for i18n string support
# RUN docker-php-ext-install mbstring

###
## Some laminas/laminas-db supported PDO extensions
###

## MySQL PDO support
RUN docker-php-ext-install pdo_mysql

## PostgreSQL PDO support
# RUN apt-get install --yes libpq-dev \
#     && docker-php-ext-install pdo_pgsql

###
## laminas/laminas-cache supported extensions
###

## APCU
# RUN pecl install apcu \
#     && docker-php-ext-enable apcu

## Memcached
# RUN apt-get install --yes libmemcached-dev \
#     && pecl install memcached \
#     && docker-php-ext-enable memcached

## MongoDB
# RUN pecl install mongodb \
#     && docker-php-ext-enable mongodb

## Redis support.  igbinary and libzstd-dev are only needed based on 
## redis pecl options
# RUN pecl install igbinary \
#     && docker-php-ext-enable igbinary \
#     && apt-get install --yes libzstd-dev \
#     && pecl install redis \
#     && docker-php-ext-enable redis

WORKDIR /var/www

# Arguments defined in docker-compose.yml
ARG user=user1
ARG uid=1000

# Create system user to run Composer
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user 

USER $user

CMD [ "composer", "serve" ]