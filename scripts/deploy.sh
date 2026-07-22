#!/usr/bin/env bash
# Deploy / update script untuk SSO Engine
# First deploy : sudo bash scripts/deploy.sh
# Update       : bash scripts/deploy.sh

set -euo pipefail

echo "=== SSO Engine — Deploy ==="

# Setup .env jika belum ada
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
    echo "[!] .env dibuat dari .env.example. Edit konfigurasi database lalu jalankan ulang."
    exit 0
fi

# Pull latest (skip jika tidak ada remote)
git pull origin main 2>/dev/null || true

# Install dependencies
composer install --no-dev --optimize-autoloader

# Migrate fresh — drop semua table dan recreate dari awal
php artisan migrate:fresh --force

# Install Passport keys + default client
php artisan passport:install --force

# Seed roles + admin user
php artisan db:seed --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "=== Deploy selesai ==="
