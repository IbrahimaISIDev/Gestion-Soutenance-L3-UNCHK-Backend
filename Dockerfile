FROM php:8.2-cli-alpine

RUN apk add --no-cache \
    libpq-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    git

RUN docker-php-ext-install \
    pdo_pgsql \
    pdo_mysql \
    mbstring \
    bcmath \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction

COPY . .

RUN composer dump-autoload --optimize \
    && mkdir -p bootstrap/cache \
       storage/framework/cache/data \
       storage/framework/sessions \
       storage/framework/views \
       storage/logs \
    && chmod -R 777 bootstrap/cache storage

EXPOSE 10000

CMD sh -c "php artisan config:cache && php artisan route:clear && php artisan migrate --force && php artisan db:seed --force && php -S 0.0.0.0:${PORT:-10000} -t public"
