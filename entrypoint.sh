#!/bin/sh
set -e

echo "Iniciando Midori Backend..."

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan migrate --force

if [ ! -f storage/oauth-private.key ]; then
    php artisan passport:install --force
fi

echo "Aplicación lista."

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}