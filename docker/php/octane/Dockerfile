FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip zip libpq-dev libpng-dev libwebp-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libgd-dev libgmp-dev autoconf make gcc g++ pkg-config cmake \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo_mysql bcmath gd zip exif gmp fileinfo pcntl \
    && pecl install redis swoole \
    && docker-php-ext-enable redis swoole \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-xlswriter \
    && php artisan octane:install

EXPOSE 8083

CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]

USER www-data
