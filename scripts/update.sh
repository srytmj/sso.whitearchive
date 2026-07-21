#!/usr/bin/env bash
# Update script — pull latest + rebuild
# Jalankan dengan: bash scripts/update.sh

set -euo pipefail

echo "=== SSO Engine — Update ==="

git pull origin main

composer install --no-dev --optimize-autoloader

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Update selesai ==="
