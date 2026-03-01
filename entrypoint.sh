#!/bin/sh
set -e

echo "Iniciando Midori Backend"

echo "Esperando a MySQL..."

until php -r "
    \$conn = @new mysqli(
        getenv('DB_HOST'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        getenv('DB_DATABASE')
    );
    exit(\$conn->connect_error ? 1 : 0);
"; do
    echo "Esperando base de datos..."
    sleep 2
done

echo "Base de datos lista!"

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Aplicación lista."

exec php-fpm