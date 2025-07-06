#!/bin/sh

# Создаем директорию, если не существует
mkdir -p /etc/nginx/conf

# Создаем файл с паролями
echo "Creating .htpasswd for ${BASIC_AUTH_USER}"
htpasswd -b -c /etc/nginx/conf/.htpasswd ${BASIC_AUTH_USER} ${BASIC_AUTH_PASSWORD}

# Проверяем создание файла
echo "Checking .htpasswd:"
ls -la /etc/nginx/conf/

# Запускаем nginx
echo "Starting nginx..."
exec nginx -g 'daemon off;'
