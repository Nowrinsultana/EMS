#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

# Source .env so shell sees its values
set -a; . .env; set +a

if grep -q "^APP_KEY=$" .env || [ -z "${APP_KEY:-}" ]; then
    unset APP_KEY
    sed -i 's/^APP_KEY=.*/APP_KEY=/' .env
    php artisan key:generate
fi

# If DB_URL or DATABASE_URL is set (via Render env var), use pgsql
if [ -n "$DB_URL" ] || [ -n "$DATABASE_URL" ]; then
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
    echo "Using PostgreSQL via DB_URL"
elif [ "$DB_CONNECTION" = "pgsql" ] && { [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ] || [ -z "$DB_HOST" ]; }; then
    echo "No external PostgreSQL — falling back to SQLite."
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env
    sed -i 's/^DB_HOST=.*//' .env
    sed -i 's/^DB_URL=.*//' .env
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
