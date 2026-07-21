#!/usr/bin/env bash
# First-time deploy wizard untuk SSO Engine di Linux server
# Jalankan dengan: sudo bash scripts/deploy.sh

set -euo pipefail

echo "=== SSO Engine — Deploy Wizard ==="
echo "Pastikan PHP 8.3+, Composer, MySQL, dan Nginx sudah terinstall."
echo ""

# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup .env jika belum ada
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
    echo "[!] .env dibuat dari .env.example. Edit konfigurasi database sebelum lanjut."
    exit 0
fi

# Migrate
php artisan migrate --force

# Install Passport
php artisan passport:install --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "=== Deploy selesai ==="
echo "Pastikan Nginx sudah pointing ke public/ folder."
