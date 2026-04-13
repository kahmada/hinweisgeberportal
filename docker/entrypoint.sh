#!/bin/bash
set -e

if [ ! -f /var/www/vendor/autoload.php ]; then
    echo "vendor/ missing — running composer install..."
    composer install --optimize-autoloader --no-interaction --prefer-dist
fi

if [ ! -f /var/www/.env ]; then
    echo ".env missing — copying from .env.example..."
    cp /var/www/.env.example /var/www/.env
fi

if ! grep -q "^APP_KEY=base64:" /var/www/.env; then
    echo "APP_KEY missing — generating..."
    php artisan key:generate --force
fi

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

echo "Waiting for MySQL to be ready..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "MySQL not ready yet, retrying in 3s..."
    sleep 3
done
echo "MySQL is ready."

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "Starting PHP-FPM..."
exec "$@"