FROM php:8.3-cli

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo_mysql gd zip bcmath pcntl \
    && pecl install redis swoole \
    && docker-php-ext-enable redis swoole \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

# 1. Копируем ТОЛЬКО файлы, необходимые для composer
COPY composer.json composer.lock artisan ./

# 2. Устанавливаем зависимости (без выполнения скриптов)
RUN composer install --no-interaction --no-scripts --optimize-autoloader

# 3. Копируем ВСЕ остальные файлы
COPY . .

# 4. Теперь выполняем скрипты post-install
RUN composer run-script post-autoload-dump

# Устанавливаем правильные права
RUN chown -R www-data:www-data /var/www

USER www-data
EXPOSE 8000
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]
