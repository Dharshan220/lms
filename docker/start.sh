#!/bin/bash
set -e

cd /var/www/html

mkdir -p /etc/nginx/sites-enabled
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/sites-available/default

chown -R www-data:www-data storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

php artisan migrate --force

chown -R www-data:www-data storage bootstrap/cache

php-fpm -D

nginx -g "daemon off;"
