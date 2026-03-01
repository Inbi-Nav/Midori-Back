#!/bin/sh
set -e

echo "Starting Midori..."

php artisan config:clear
php artisan cache:clear

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

chmod -R 775 /var/www/storage

php artisan migrate --force

if [ ! -f /var/www/storage/oauth-private.key ]; then
    php artisan passport:install --force
fi

chmod 600 /var/www/storage/oauth-private.key
chmod 644 /var/www/storage/oauth-public.key

echo "App ready."

exec php -S 0.0.0.0:$PORT -t public