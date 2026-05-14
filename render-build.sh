#!/usr/bin/env bash
# Render build script for Laravel with PostgreSQL

set -e

echo "==> Building Laravel application..."

echo "==> Installing Composer dependencies..."
composer install --no-interaction --optimize-autoloader --no-dev

echo "==> Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Build complete!"
