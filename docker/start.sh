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
require __DIR__.'/vendor/autoload.php';
\$app = require_once __DIR__.'/bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();
\$u = App\Models\User::where('email','admin@nanospark.com')->first();
if (!\$u) { App\Models\User::create(['name'=>'Super Admin','email'=>'admin@nanospark.com','password'=>bcrypt('password'),'role'=>'super_admin','is_active'=>true,'email_verified_at'=>now()]); echo \"Created admin\n\"; } else { echo \"Admin exists\n\"; }
\$u2 = App\Models\User::where('email','student1@nanospark.com')->first();
if (!\$u2) { App\Models\User::create(['name'=>'Student 1','email'=>'student1@nanospark.com','password'=>bcrypt('password'),'role'=>'student','is_active'=>true,'email_verified_at'=>now()]); echo \"Created student\n\"; } else { echo \"Student exists\n\"; }
" || true

chown -R www-data:www-data storage bootstrap/cache

php-fpm -D

nginx -g "daemon off;"
