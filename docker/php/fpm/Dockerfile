FROM php:8.3-fpm

# Рабочая директория
WORKDIR /var/www

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    libpq-dev \
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
    fileinfo \
    pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Копируем кастомный php.ini
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

COPY . .

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-xlswriter

# Устанавливаем владельца
RUN chown -R www-data:www-data /var/www

# Пользователь по умолчанию
USER www-data
