FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
        git \
        unzip \
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

WORKDIR /var/www

USER www-data

# Копируем composer и только нужные файлы, чтобы не сработал artisan discover
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock artisan bootstrap/app.php ./

# Копируем остальное
COPY . .

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/php.ini
