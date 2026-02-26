set -e

echo "========================================"
echo "Iniciando entrypoint de Midori Backend"
echo "========================================"

echo " Esperando a MySQL..."
max_attempts=30
attempt=1
while [ $attempt -le $max_attempts ]; do
    php -r "
        \$conn = @new mysqli('mysql', 'root', 'Admin_1234', 'midori');
        if (!\$conn->connect_error) {
            exit(0);
        }
        exit(1);
    " && {
        echo " MySQL está listo!"
        break
    }
    echo "Intento $attempt de $max_attempts..."
    sleep 2
    attempt=$((attempt + 1))
done

if [ $attempt -gt $max_attempts ]; then
    echo " Error: No se pudo conectar a MySQL"
    exit 1
fi

if ! grep -q "APP_KEY=" .env || [ -z "$(grep APP_KEY .env | cut -d '=' -f2)" ]; then
    echo "Generando APP_KEY..."
    php artisan key:generate --force
else
    echo " APP_KEY ya existe"
fi

echo " Ejecutando migraciones..."
php artisan migrate --force

echo " Ejecutando seeds..."
php artisan db:seed --force

echo " Generando claves de Passport..."
php artisan passport:keys --force

if [ $(php artisan tinker --execute="echo Laravel\Passport\Client::count();") -eq 0 ]; then
    echo " Creando clientes de Passport..."
    php artisan passport:install --force
else
    echo " Clientes de Passport ya existen"
fi

echo " Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "========================================"
echo "Backend listo! Servidor en puerto 8000"
echo "========================================"

exec php artisan serve --host=0.0.0.0 --port=8000