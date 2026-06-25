#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if grep -q "^APP_KEY=$" .env || [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

DB_CONNECTION=${DB_CONNECTION:-sqlite}

if [ "$DB_CONNECTION" = "sqlite" ]; then
    touch database/database.sqlite
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force

PORT=${PORT:-8080}

php artisan serve --host=0.0.0.0 --port=$PORT
