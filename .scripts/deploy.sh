#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Install composer dependencies
git reset --hard HEAD
git pull origin main --no-ff
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
composer dump-autoload
php artisan cache:clear
php artisan config:cache
php artisan config:clear
php artisan route:cache
php artisan view:cache
php artisan storage:link
# Exit maintenance mode
php artisan up

echo "Deployment finished!"