#!/bin/bash
set -e

cd /var/www/html

# Ensure nginx config is linked
mkdir -p /etc/nginx/sites-enabled
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Fix storage permissions before artisan commands
chown -R www-data:www-data storage bootstrap/cache

# Run artisan commands that need env vars (available at runtime via Render env vars)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true
php artisan migrate --force || true

# Fix permissions again after storage:link
chown -R www-data:www-data storage bootstrap/cache

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground
nginx -g "daemon off;"
