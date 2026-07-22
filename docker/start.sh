#!/bin/bash
set -e

cd /var/www/html

mkdir -p /etc/nginx/sites-enabled
rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

sed -i "s/listen 80;/listen ${PORT:-80};/g" /etc/nginx/sites-available/default

# Ensure storage dirs exist
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan storage:link 2>/dev/null || true

# Cache config (allow failure if env vars not ready)
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Run migrations (allow failure so nginx still starts)
php artisan migrate --force 2>/dev/null || echo "Migration failed - will retry on next deploy"
php artisan db:seed --force 2>/dev/null || true
php -r "
require __DIR__.'/vendor/autoload.php';
\$app = require_once __DIR__.'/bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();
\$u = App\Models\User::where('email','admin@nanospark.com')->first();
if (!\$u) { App\Models\User::create(['name'=>'Super Admin','email'=>'admin@nanospark.com','password'=>bcrypt('password'),'role'=>'super_admin','is_active'=>true,'email_verified_at'=>now()]); echo \"Created admin\n\"; } else { echo \"Admin exists\n\"; }
\$u2 = App\Models\User::where('email','student1@nanospark.com')->first();
if (!\$u2) { App\Models\User::create(['name'=>'Student 1','email'=>'student1@nanospark.com','password'=>bcrypt('password'),'role'=>'student','is_active'=>true,'email_verified_at'=>now()]); echo \"Created student\n\"; } else { echo \"Student exists\n\"; }
\$emails = ['priya@nanospark.com'=>'Dr. Priya Sharma','rahul@nanospark.com'=>'Prof. Rahul Verma','anita@nanospark.com'=>'Ms. Anita Patel','schooladmin1@nanospark.com'=>'DPS Admin','parent@nanospark.com'=>'Parent User'];
foreach (\$emails as \$e=>\$n) { \$u = App\Models\User::where('email',\$e)->first(); if (!\$u) { App\Models\User::create(['name'=>\$n,'email'=>\$e,'password'=>bcrypt('password'),'role'=>str_contains(\$e,'school')?'school_admin':(str_contains(\$e,'parent')?'parent':'teacher'),'is_active'=>true,'email_verified_at'=>now()]); echo \"Created \$e\n\"; } else { echo \"\$e exists\n\"; } }
" || true

chown -R www-data:www-data storage bootstrap/cache

php-fpm -D

nginx -g "daemon off;"
