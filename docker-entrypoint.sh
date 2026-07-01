#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if grep -q "^APP_KEY=$" .env || [ -z "${APP_KEY:-}" ]; then
    unset APP_KEY
    sed -i 's/^APP_KEY=.*/APP_KEY=/' .env
    php artisan key:generate
fi

if [ -n "$DB_URL" ]; then
    if grep -q "^DB_CONNECTION=" .env; then
        sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
    else
        echo "DB_CONNECTION=pgsql" >> .env
    fi
elif [ "$DB_CONNECTION" = "pgsql" ] && { [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ] || [ -z "$DB_HOST" ]; }; then
    echo "No external PostgreSQL configured — falling back to SQLite."
    export DB_CONNECTION=sqlite
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
    sed -i 's/^DB_HOST=.*/DB_HOST=/' .env
    touch database/database.sqlite
fi

if [ -n "$RENDER_EXTERNAL_URL" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
    export ASSET_URL="$RENDER_EXTERNAL_URL"
    sed -i "s|^APP_URL=.*|APP_URL=$RENDER_EXTERNAL_URL|" .env
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force

php artisan db:seed --class=DatabaseSeeder --no-interaction

php artisan storage:link --force 2>/dev/null || true

PORT=${PORT:-8080}

php artisan serve --host=0.0.0.0 --port=$PORT
