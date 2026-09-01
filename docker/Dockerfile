FROM php:5.6-apache

# Debian Stretch is archived; PHP 5.6 images still need these mirrors.
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && sed -i 's|security.debian.org|archive.debian.org|g' /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "0";' > /etc/apt/apt.conf.d/99no-check-valid-until

RUN apt-get update && apt-get install -y --allow-unauthenticated \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libmcrypt-dev \
        libxml2-dev \
        libicu-dev \
        zlib1g-dev \
        libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install \
        mysql \
        mysqli \
        pdo \
        pdo_mysql \
        mcrypt \
        gd \
        zip \
        soap \
        mbstring \
        calendar \
        bcmath \
        intl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY php.ini /usr/local/etc/php/conf.d/infosys.ini
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY index.html /var/www/html/index.html
