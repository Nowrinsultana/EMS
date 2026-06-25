#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if grep -q "^APP_KEY=$" .env || [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

if [ "$DB_CONNECTION" != "sqlite" ] && { [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ] || [ -z "$DB_HOST" ]; }; then
    export DB_CONNECTION=sqlite
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
    sed -i 's/^DB_HOST=.*/DB_HOST=/' .env
fi

if [ "$DB_CONNECTION" = "sqlite" ]; then
    touch database/database.sqlite
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force

PORT=${PORT:-8080}

php artisan serve --host=0.0.0.0 --port=$PORT
