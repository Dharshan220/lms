#!/bin/bash
set -e

# Create .env file from environment variables
cat > /var/www/html/.env <<EOF
APP_NAME=Nano Spark LMS
APP_ENV=production
APP_KEY=${APP_KEY:-}
APP_DEBUG=false
APP_URL=${APP_URL:-https://nanosparklms-n6dc.onrender.com}
LOG_CHANNEL=stderr
LOG_LEVEL=debug
DB_CONNECTION=${DB_CONNECTION:-pgsql}
DB_HOST=${DB_HOST:-}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE:-postgres}
DB_USERNAME=${DB_USERNAME:-postgres}
DB_PASSWORD=${DB_PASSWORD:-}
BROADCAST_DRIVER=log
CACHE_DRIVER=array
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@nanospark.com
MAIL_FROM_NAME=Nano Spark LMS
EOF

# Create storage directories
mkdir -p storage/framework/{cache/data,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generate APP_KEY if empty
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    php artisan key:generate --force
fi

# Clear ALL caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force || true

# Start server
exec php artisan serve --host=0.0.0.0 --port=8000
