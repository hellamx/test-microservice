FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libwebp-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libgmp-dev \
    pkg-config \
    autoconf \
    make \
    gcc \
    g++ \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        zip \
        bcmath \
        gd \
        gmp \
        exif \
        fileinfo \
        pcntl \
    && pecl install redis swoole \
    && docker-php-ext-enable redis swoole

# xlswriter
RUN git clone --depth=1 https://github.com/viest/php-ext-xlswriter.git /tmp/xlswriter \
    && cd /tmp/xlswriter \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable xlswriter \
    && rm -rf /tmp/xlswriter

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY .. .

RUN composer install --no-dev --optimize-autoloader \
    && php artisan octane:install

EXPOSE 8083

CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]
