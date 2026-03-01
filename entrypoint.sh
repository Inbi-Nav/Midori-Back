#!/bin/sh
set -e

echo "Starting Midori..."

php artisan config:clear
php artisan cache:clear

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan migrate:fresh --seed --force

echo "App ready."

exec php -S 0.0.0.0:$PORT -t public