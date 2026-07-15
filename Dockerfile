FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libicu-dev libonig-dev \
    && docker-php-ext-install pdo_pgsql mbstring intl bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 8000

CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan migrate --force && php artisan db:seed --force; php artisan serve --host=0.0.0.0 --port=8000"]
