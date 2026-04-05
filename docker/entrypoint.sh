#!/bin/bash
set -e

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
