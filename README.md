# Микро-сервис обработки платежей

- **Nginx** — веб-сервер и обратный прокси
- **PHP-FPM** — обработка стандартных HTTP-запросов Laravel и API для внутренней админки `/api/internal/xxx`
- **PHP Octane** — laravel octane на базе swoole для внешних API `/api/external/xxx`
- **Redis** — для очередей
- **MariaDB** — бд
- **Horizon** — мониторинг и управление очередями `/horizon`
- **phpMyAdmin** — веб интерфейс для бд
- **Node** — для сборки фронтенда

---

## Инструкция по разворачиванию

1. Копия `.env`:

    ```bash
    cp .env.example .env
    ```
   
2. Composer

    ```bash 
   curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer && sudo chmod +x /usr/local/bin/composer 
   composer install --optimize-autoloader --ignore-platform-req=ext-xlswriter
   ```

3. Сборка контейнеров:

    ```bash
    docker compose up -d --build
    ```

4. Настройка прав

    ```bash
    sudo usermod -aG www-data $USER
    ```

    ```bash
    sudo chown -R www-data:www-data .
    ```

    ```bash
    sudo chmod -R 775 storage bootstrap/cache
    ```

5. Настройка `laravel`:

    ```bash
    docker exec -it php bash
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    php artisan config:cache
    ```

    ```bash
    docker compose down && docker compose up -d --build
    ```
       
    ```bash 
    docker exec php_octane php artisan octane:install
    ```

6. Сборка фронтенда:

    ```bash
    docker exec -it node bash

    npm install
    npm run build
    ```
   
## Интерфейсы:

    - Админка и внутреннее API защищены basic auth на уровне nginx (по умолчанию login: `admin`, pass: `admin`)
    - Админка: [http://localhost:8083](http://localhost:8083)
    - phpMyAdmin: [http://localhost:8082](http://localhost:8082) (по умолчанию login: `dev`, pass: `dev`)
    - Horizon: [http://localhost:8083/horizon](http://localhost:8083/horizon)

---

## Пример с POSTMAN

* В репозитории есть тестовая коллекция `Payments_API.postman_collection.json`
* Там подготовлен запрос с созданием платежа и заранее вычисленным заголовком X-Signature для подписи HMAC
* Секрет HMAC - "test"

    ```json
        {"data":{"payment_id":"8403db96-3b4a-4dd1-bf9d-de87d53007c5","login":"test","project_name":"test","details":"1234567890123","amount":10460.74,"currency":"RUB","status":"UNPAID"}}
    ```
  
    ```json
        {"X-Signature": "64e2667cb287ce52f3449fea815e9129b30f0d6553cd11d0c6341e1218129239"}
    ```
