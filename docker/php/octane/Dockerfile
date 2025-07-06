FROM php:8.3-cli

# Устанавливаем нужные зависимости и расширения
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip zip curl libpng-dev libjpeg-dev libfreetype6-dev \
    libwebp-dev libzip-dev libgd-dev libgmp-dev autoconf make gcc g++ pkg-config cmake \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo_mysql bcmath gd zip exif gmp fileinfo pcntl \
    && pecl install redis swoole \
    && docker-php-ext-enable redis swoole \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www

COPY . .

RUN chown -R www-data:www-data /var/www
USER www-data

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]
