#!/bin/sh
set -e

echo "Iniciando Midori Backend..."

php artisan config:clear
php artisan cache:clear

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan vendor:publish --tag=passport-migrations --force

php artisan migrate --force

php artisan passport:install --force

echo "Aplicación lista."

exec php artisan serve --host=0.0.0.0 --port=$PORT