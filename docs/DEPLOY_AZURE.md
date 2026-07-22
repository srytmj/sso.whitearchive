# Deploy ke Azure — SSO Engine

Panduan deploy `sso.whitearchive.id` ke Azure menggunakan **Azure App Service** (cara paling mudah) atau **Azure VM** (lebih kontrol, lebih mirip VPS).

---

## Pilih Pendekatan

| | App Service | Azure VM |
|---|---|---|
| Setup | Lebih mudah, GUI | Manual, SSH |
| Kontrol | Terbatas | Penuh |
| Harga | Dari ~$13/bln (B1) | Dari ~$7/bln (B1s) |
| Cocok untuk | Cepat production-ready | Fleksibel, multi-service |

**Rekomendasi**: Azure VM jika sudah terbiasa Linux server. App Service jika mau setup cepat tanpa manage server.

---

## Cara A — Azure VM (Rekomendasi)

### 1. Buat VM di Azure Portal

1. Buka [portal.azure.com](https://portal.azure.com) → **Create a resource** → **Virtual Machine**
2. Konfigurasi:
   - **Image**: Ubuntu Server 24.04 LTS
   - **Size**: B1s (1 vCPU, 1GB RAM) — cukup untuk mulai
   - **Authentication**: SSH public key
   - **Inbound ports**: buka port 22 (SSH), 80 (HTTP), 443 (HTTPS)
3. Klik **Review + Create** → **Create**
4. Download private key `.pem` saat diminta — simpan baik-baik

### 2. Connect ke VM

```bash
chmod 400 your-key.pem
ssh -i your-key.pem azureuser@<PUBLIC_IP>
```

### 3. Install Dependencies di VM

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3 + extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring \
    php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-tokenizer

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Nginx
sudo apt install -y nginx

# Install MySQL
sudo apt install -y mysql-server
sudo mysql_secure_installation

# Install Node (untuk Vite build)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install Git
sudo apt install -y git
```

### 4. Setup Database

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE db_sso CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sso_user'@'localhost' IDENTIFIED BY 'strong-password-here';
GRANT ALL PRIVILEGES ON db_sso.* TO 'sso_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Clone & Setup Project

```bash
cd /var/www
sudo git clone https://github.com/srytmj/sso.whitearchive.git sso
sudo chown -R azureuser:azureuser /var/www/sso
cd /var/www/sso

# Copy .env dan isi konfigurasi
cp .env.example .env
nano .env
```

Isi `.env` yang wajib diubah:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sso.whitearchive.id

DB_HOST=127.0.0.1
DB_DATABASE=db_sso
DB_USERNAME=sso_user
DB_PASSWORD=strong-password-here

SESSION_DRIVER=database

MAIL_MAILER=resend
RESEND_API_KEY=re_xxx
MAIL_FROM_ADDRESS=noreply@whitearchive.id

ADMIN_EMAIL=your@email.com
ADMIN_PASSWORD=strong-admin-password
```

```bash
# Jalankan deploy script (sama dengan make deploy)
sudo bash scripts/deploy.sh

# Build assets
npm install && npm run build

# Seed roles + admin user
php artisan db:seed
```

### 6. Konfigurasi Nginx

```bash
sudo nano /etc/nginx/sites-available/sso
```

```nginx
server {
    listen 80;
    server_name sso.whitearchive.id;
    root /var/www/sso/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Aktifkan site
sudo ln -s /etc/nginx/sites-available/sso /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

# Set permission storage
sudo chown -R www-data:www-data /var/www/sso/storage /var/www/sso/bootstrap/cache
sudo chmod -R 775 /var/www/sso/storage /var/www/sso/bootstrap/cache
```

### 7. Setup HTTPS via Let's Encrypt

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d sso.whitearchive.id

# Auto-renewal
sudo systemctl enable certbot.timer
```

### 8. Arahkan Domain ke VM

Di Cloudflare DNS:
- Tambah **A record**: `sso` → `<PUBLIC_IP_VM>` (proxy: ON)
- Pastikan SSL/TLS mode di Cloudflare: **Full (strict)**

### 9. Verifikasi

```bash
# Cek semua service jalan
sudo systemctl status nginx
sudo systemctl status php8.3-fpm
sudo systemctl status mysql

# Test akses
curl -I https://sso.whitearchive.id
```

---

## Cara B — Azure App Service

### 1. Buat App Service

1. Azure Portal → **Create a resource** → **Web App**
2. Konfigurasi:
   - **Runtime stack**: PHP 8.3
   - **Operating System**: Linux
   - **Region**: Southeast Asia (terdekat)
   - **Plan**: B1 (Basic)
3. Klik **Create**

### 2. Buat Azure Database for MySQL

1. Azure Portal → **Create a resource** → **Azure Database for MySQL**
2. Pilih **Flexible Server**
3. Catat hostname, username, password
4. Di **Networking**: tambah firewall rule untuk App Service IP

### 3. Setup Deployment via GitHub Actions

Buat file `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Azure

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'

      - name: Install dependencies
        run: composer install --no-dev --optimize-autoloader

      - name: Setup Node & build assets
        run: |
          npm install
          npm run build

      - name: Deploy to Azure Web App
        uses: azure/webapps-deploy@v3
        with:
          app-name: ${{ secrets.AZURE_WEBAPP_NAME }}
          publish-profile: ${{ secrets.AZURE_WEBAPP_PUBLISH_PROFILE }}
```

### 4. Set Environment Variables

Di Azure Portal → App Service → **Configuration** → **Application settings**, tambahkan semua isi `.env`:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sso.whitearchive.id
DB_HOST=<azure-mysql-hostname>
DB_DATABASE=db_sso
DB_USERNAME=sso_user
DB_PASSWORD=xxx
SESSION_DRIVER=database
RESEND_API_KEY=re_xxx
# ... dst
```

### 5. Jalankan Migration (sekali)

Di Azure Portal → App Service → **SSH** atau via **Console**:

```bash
cd /home/site/wwwroot
php artisan migrate --force
php artisan passport:install --force
php artisan db:seed
php artisan config:cache && php artisan route:cache
```

---

## Update (Kedua Cara)

### Azure VM

**Dari lokal** (tanpa perlu SSH manual — butuh `SERVER_HOST` di `.env`):

```bash
make remote-update
```

**Manual via SSH**:

```bash
make ssh
# atau: ssh -i your-key.pem azureuser@<IP>

cd /var/www/sso
bash scripts/update.sh   # sama dengan: make update

# Jika ada perubahan assets:
npm run build
sudo systemctl reload nginx
```

### App Service

GitHub Actions otomatis deploy saat push ke `main`.

---

## Make Commands (Azure VM)

Tambahkan variabel berikut ke `.env` lokal untuk enable remote commands:

```env
SERVER_HOST=<public-ip-vm>
SSH_KEY_PATH=~/.ssh/your-key.pem
SERVER_USER=azureuser
SERVER_PATH=/var/www/sso
```

| Command | Keterangan |
|---------|------------|
| `make ssh` | SSH ke server |
| `make remote-deploy` | First-time deploy dari lokal via SSH |
| `make remote-update` | Pull latest + rebuild dari lokal via SSH |
| `make deploy` | First-time deploy (dijalankan **di dalam** server) |
| `make update` | Pull latest + rebuild (dijalankan **di dalam** server) |

---

## Checklist Post-Deploy

- [ ] `https://sso.whitearchive.id` bisa diakses, redirect ke landing page
- [ ] Login sebagai superadmin berhasil
- [ ] `GET https://sso.whitearchive.id/api/user` dengan token valid → return JSON
- [ ] Buat OAuth client di dashboard → Quick Start panel muncul
- [ ] Forgot password → email terkirim via Resend (pastikan `RESEND_API_KEY` diisi dan domain terverifikasi)
- [ ] SSL aktif, tidak ada mixed content warning
- [ ] `APP_DEBUG=false` di production — pastikan error tidak expose stack trace

---

## Troubleshooting

| Problem | Solusi |
|---------|--------|
| 500 error | Cek `storage/logs/laravel.log` |
| 403 Forbidden | `sudo chown -R www-data:www-data storage/ bootstrap/cache/` |
| DB connection failed | Cek `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` di `.env` |
| Passport error | `php artisan passport:install --force` |
| Assets tidak muncul | `npm run build` belum dijalankan |
| Session tidak persist | Pastikan `SESSION_DRIVER=database` dan migration sessions sudah jalan |
