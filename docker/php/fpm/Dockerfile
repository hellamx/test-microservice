FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
        git \
        unzip \
        curl \
        libpng-dev \
        libwebp-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        libgd-dev \
        libgmp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        gd \
        zip \
        exif \
        gmp \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Установка composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

WORKDIR /var/www

COPY . .

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

RUN chown -R www-data:www-data /var/www
USER www-data
