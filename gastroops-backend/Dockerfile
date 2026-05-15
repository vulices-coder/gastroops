FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD php artisan optimize:clear && php artisan migrate --force && php artisan route:list && php artisan serve --host=0.0.0.0 --port=${PORT}