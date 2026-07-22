# Deploy ke AWS — SSO Engine

Panduan deploy `sso.whitearchive.id` ke AWS menggunakan **EC2** (mirip VPS, paling fleksibel) atau **Elastic Beanstalk** (managed, auto-scaling).

---

## Pilih Pendekatan

| | EC2 | Elastic Beanstalk |
|---|---|---|
| Setup | Manual via SSH | CLI / console |
| Kontrol | Penuh | Terbatas |
| Harga | Dari ~$8/bln (t3.micro) | EC2 + overhead ~$10-15/bln |
| Cocok untuk | Full control, mirip VPS | Cepat deploy, auto-scaling |

**Rekomendasi**: EC2 — paling mirip dengan workflow `make deploy` yang sudah ada dan paling mudah di-debug.

---

## Cara A — EC2 (Rekomendasi)

### 1. Buat EC2 Instance

1. Buka [console.aws.amazon.com](https://console.aws.amazon.com) → **EC2** → **Launch Instance**
2. Konfigurasi:
   - **Name**: `sso-whitearchive`
   - **AMI**: Ubuntu Server 24.04 LTS (Free Tier eligible)
   - **Instance type**: `t3.micro` (2 vCPU, 1GB RAM) — cukup untuk mulai
   - **Key pair**: Create new → download `.pem` → simpan baik-baik
   - **Security Group** — buka inbound:
     - SSH (22) dari IP kamu saja
     - HTTP (80) dari anywhere
     - HTTPS (443) dari anywhere
3. Klik **Launch Instance**

### 2. Alokasi Elastic IP (Opsional tapi Direkomendasikan)

IP EC2 berubah setiap restart jika tidak pakai Elastic IP:

1. EC2 → **Elastic IPs** → **Allocate Elastic IP**
2. **Associate** ke instance yang baru dibuat
3. Gunakan Elastic IP ini untuk DNS record

### 3. Connect ke Instance

```bash
chmod 400 your-key.pem
ssh -i your-key.pem ubuntu@<ELASTIC_IP_ATAU_PUBLIC_IP>
```

### 4. Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3 + extensions
sudo add-apt-repository ppa:ondrej/php -y
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

### 5. Setup Database

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

### 6. Clone & Setup Project

```bash
cd /var/www
sudo git clone https://github.com/srytmj/sso.whitearchive.git sso
sudo chown -R ubuntu:ubuntu /var/www/sso
cd /var/www/sso

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
# Jalankan deploy script
sudo bash scripts/deploy.sh

# Build assets
npm install && npm run build

# Seed roles + admin user
php artisan db:seed
```

### 7. Konfigurasi Nginx

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
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx

# Set permission storage
sudo chown -R www-data:www-data /var/www/sso/storage /var/www/sso/bootstrap/cache
sudo chmod -R 775 /var/www/sso/storage /var/www/sso/bootstrap/cache
```

### 8. Setup HTTPS via Let's Encrypt

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d sso.whitearchive.id

# Auto-renewal
sudo systemctl enable certbot.timer
```

### 9. Arahkan Domain ke EC2

Di Cloudflare DNS:
- Tambah **A record**: `sso` → `<ELASTIC_IP>` (proxy: ON)
- SSL/TLS mode di Cloudflare: **Full (strict)**

### 10. Verifikasi

```bash
sudo systemctl status nginx
sudo systemctl status php8.3-fpm
sudo systemctl status mysql

curl -I https://sso.whitearchive.id
```

---

## Cara B — Elastic Beanstalk

### 1. Install EB CLI

```bash
pip install awsebcli
aws configure  # isi Access Key ID + Secret dari IAM
```

### 2. Buat RDS (MySQL Managed)

1. AWS Console → **RDS** → **Create database**
2. Engine: MySQL 8.0, Template: Free tier
3. DB identifier: `sso-db`, username: `sso_user`, password: isi sendiri
4. Catat **Endpoint** (hostname) setelah database ready

### 3. Init Elastic Beanstalk

```bash
cd /path/to/sso.whitearchive

eb init
# Pilih region, platform: PHP 8.3, create new app: sso-whitearchive
```

Buat `.ebextensions/nginx.conf` untuk PHP-FPM:

```yaml
# .ebextensions/laravel.config
option_settings:
  aws:elasticbeanstalk:container:php:phpini:
    document_root: /public
  aws:elasticbeanstalk:application:environment:
    APP_ENV: production
    APP_DEBUG: false
```

### 4. Set Environment Variables

```bash
eb setenv \
  APP_ENV=production \
  APP_DEBUG=false \
  APP_URL=https://sso.whitearchive.id \
  APP_KEY=$(php artisan key:generate --show) \
  DB_HOST=<rds-endpoint> \
  DB_DATABASE=db_sso \
  DB_USERNAME=sso_user \
  DB_PASSWORD=xxx \
  SESSION_DRIVER=database \
  RESEND_API_KEY=re_xxx \
  MAIL_FROM_ADDRESS=noreply@whitearchive.id
```

### 5. Deploy

```bash
eb create sso-production
# atau update setelah ada:
eb deploy
```

### 6. Jalankan Migration (sekali)

```bash
eb ssh
cd /var/app/current
php artisan migrate --force
php artisan passport:install --force
php artisan db:seed
```

### 7. Custom Domain + HTTPS

1. Buka EB environment → **Configuration** → **Load Balancer** → tambah HTTPS listener (port 443)
2. Upload SSL certificate via **AWS Certificate Manager** (ACM) — request certificate untuk `sso.whitearchive.id`
3. Cloudflare DNS: CNAME `sso` → EB environment URL

---

## Update (Kedua Cara)

```bash
# EC2 — SSH ke server lalu:
cd /var/www/sso
bash scripts/update.sh

# Jika ada perubahan assets:
npm run build
sudo systemctl reload nginx

# Elastic Beanstalk:
eb deploy
```

---

## Opsional: RDS sebagai Database di EC2

Kalau mau MySQL di server terpisah (lebih production-grade dari MySQL lokal):

1. AWS Console → RDS → Create database → MySQL 8.0
2. **VPC Security Group**: izinkan koneksi dari EC2 Security Group ke port 3306
3. Update `.env` di EC2:
   ```env
   DB_HOST=<rds-endpoint>
   DB_READ_HOST=<rds-endpoint>
   DB_WRITE_HOST=<rds-endpoint>
   ```

---

## Checklist Post-Deploy

- [ ] `https://sso.whitearchive.id` accessible, tampil landing page
- [ ] Login superadmin berhasil
- [ ] `GET /api/user` dengan token valid → return JSON profil
- [ ] Create OAuth client di dashboard → Quick Start panel muncul
- [ ] Forgot password → email terkirim (Resend domain harus sudah verified)
- [ ] SSL aktif, tidak ada mixed content warning
- [ ] `APP_DEBUG=false` — error tidak expose stack trace ke browser
- [ ] `storage/` dan `bootstrap/cache/` writable oleh `www-data`

---

## Troubleshooting

| Problem | Solusi |
|---------|--------|
| 500 error | `tail -f /var/www/sso/storage/logs/laravel.log` |
| 403 Forbidden | `sudo chown -R www-data:www-data /var/www/sso/storage /var/www/sso/bootstrap/cache` |
| DB connection failed | Cek `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` di `.env` — jika RDS, cek Security Group |
| Passport keys error | `php artisan passport:install --force` |
| Assets 404 | `npm run build` belum dijalankan |
| HTTPS redirect loop | Di Cloudflare SSL/TLS mode pastikan **Full (strict)**, bukan Flexible |
| Session tidak persist | `SESSION_DRIVER=database` dan `php artisan session:table && php artisan migrate` |
