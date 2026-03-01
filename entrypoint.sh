#!/bin/sh
set -e

echo "Iniciando Midori Backend..."

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan migrate --force

echo "Aplicación lista."

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}