#!/bin/bash
set -e

# Allow git to operate on the bind-mounted project directory
git config --global --add safe.directory /var/www

cd /var/www

# Configure Flux Pro auth if credentials are provided.
# This must run before every `composer install`, because `php artisan flux:activate`
# stores the license in the *global* auth.json, which does not survive an image rebuild.
if [ -n "$FLUX_USERNAME" ] && [ -n "$FLUX_PASSWORD" ]; then
    composer config --auth http-basic.composer.fluxui.dev "$FLUX_USERNAME" "$FLUX_PASSWORD"
fi

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies if needed
if [ ! -d "node_modules" ]; then
    npm install
fi

# Build front-end assets if not already built
if [ ! -d "public/build" ]; then
    npm run build
fi

# Set up .env if it doesn't exist
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Generate app key if not set
if grep -q "^APP_KEY=$" .env; then
    php artisan key:generate --no-interaction
fi

# Load .env variables into the shell environment if the file exists
if [ -f ".env" ]; then
    set -a
    # shellcheck disable=SC1091
    source .env
    set +a
fi

# Wait for MySQL to be ready
if grep -q "DB_CONNECTION=mysql" .env 2>/dev/null; then
    echo "Waiting for MySQL..."
    until php -r "new PDO('mysql:host=${DB_HOST:-mysql};port=${DB_PORT:-3306};dbname=${DB_DATABASE:-laravel}', '${DB_USERNAME:-root}', '${DB_PASSWORD:-}');" 2>/dev/null; do
        sleep 2
    done
    echo "MySQL is ready."
fi

# Run migrations
php artisan migrate --force --no-interaction

# Fix storage and cache permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

exec "$@"
