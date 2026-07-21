#!/bin/bash
set -e

cd /var/www/html

mkdir -p /etc/nginx/sites-enabled
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/sites-available/default

chown -R www-data:www-data storage bootstrap/cache

php artisan storage:link || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

php artisan migrate --force
php artisan db:seed --force || true
php -r "
\$app = require __DIR__.'/bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();
\$u = App\Models\User::where('email','admin@nanospark.com')->first();
if (!\$u) { App\Models\User::create(['name'=>'Super Admin','email'=>'admin@nanospark.com','password'=>bcrypt('password'),'role'=>'super_admin','is_active'=>true,'email_verified_at'=>now()]); echo \"Created\n\"; } else { echo \"Exists\n\"; }
" || true

chown -R www-data:www-data storage bootstrap/cache

php-fpm -D

nginx -g "daemon off;"
