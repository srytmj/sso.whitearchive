# SSO Engine — whitearchive.id

Central Identity Provider untuk ekosistem whitearchive.id. User login sekali, bisa akses semua aplikasi (Malas, Scribe, dll.) tanpa login ulang.

**Protokol**: OAuth2 Authorization Code + PKCE (RFC 6749 + RFC 7636)
**URL produksi**: `https://sso.suryatmaja.dev` *(sementara — akan pindah ke `sso.whitearchive.id`)*

---

## Stack

- **Backend**: Laravel (latest stable) + Laravel Passport
- **Frontend**: Blade + Alpine.js + Tailwind CSS (no SPA)
- **Database**: MySQL — `db_sso` (read/write split)
- **Email**: Resend
- **Infra**: Linux VM / EC2, Cloudflare DNS + proxy

---

## Setup Lokal

**Prasyarat**: PHP 8.2+, Composer, MySQL

```bash
git clone <repo-url> sso.whitearchive
cd sso.whitearchive

composer install
cp .env.example .env
php artisan key:generate
```

Buat database `db_sso` di MySQL, lalu isi `.env`:

```env
DB_DATABASE=db_sso
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=resend
RESEND_API_KEY=re_xxx
MAIL_FROM_ADDRESS=noreply@whitearchive.id
MAIL_FROM_NAME="SSO whitearchive.id"
```

```bash
php artisan migrate
php artisan passport:install
php artisan db:seed          # seed roles + superadmin
php artisan serve
```

Akses di `http://localhost:8000`.

---

## Deployment

**First deploy** (server baru):
```bash
make deploy
# atau: sudo bash scripts/deploy.sh
```

**Update** (kode sudah di server):
```bash
make update
# atau: bash scripts/update.sh
```

Tidak ada CI/CD — deploy manual via SSH.

---

## Arsitektur

```
Request → Controller (validasi) → Service/Action (logic) → Model → Response
```

- Controller: thin, hanya validasi dan delegasi
- Service: business logic
- Action: single-responsibility (contoh: `RevokeTokenAction`)
- Model: relasi dan scopes saja

```
app/
  Http/
    Controllers/Auth/     # Login, Register, Logout, ForgotPassword, ResetPassword
    Controllers/Api/      # UserController (GET /api/user)
  Services/Auth/          # LoginService, RegisterService
  Actions/Auth/           # RevokeTokenAction
  Models/                 # User, Role
resources/views/
  layouts/                # public, auth, dashboard, account
  auth/                   # login, register, forgot-password, reset-password
docs/
  PRD.md                  # Product requirements
  SRS.md                  # Tech spec & API contract
  INTEGRATION.md          # Panduan integrasi untuk developer client app
  tickets/                # TASK-001 s/d TASK-017
```

---

## Endpoints Utama

| Endpoint | Keterangan |
|----------|------------|
| `GET /` | Landing page |
| `GET /login` | Halaman login |
| `GET /register` | Halaman register |
| `GET /forgot-password` | Form lupa password |
| `GET /oauth/authorize` | Titik masuk OAuth2 flow (Passport) |
| `POST /oauth/token` | Tukar code/refresh token (Passport) |
| `GET /api/user` | Profil user (butuh Bearer token + scope `profile:read`) |
| `GET /account` | My Account (auth) |
| `GET /dashboard` | Dashboard superadmin |

---

## Integrasi Client App

Lihat [docs/INTEGRATION.md](docs/INTEGRATION.md) untuk panduan lengkap OAuth2 flow, contoh kode, dan checklist integrasi.

---

## Docs

| File | Isi |
|------|-----|
| [docs/PRD.md](docs/PRD.md) | Product requirements & user stories |
| [docs/SRS.md](docs/SRS.md) | Tech spec, DB schema, API contract |
| [docs/INTEGRATION.md](docs/INTEGRATION.md) | Panduan integrasi untuk developer (manual) |
| [docs/AI_INTEGRATION.md](docs/AI_INTEGRATION.md) | Brief integrasi untuk AI assistant |
| [docs/DEPLOY_AZURE.md](docs/DEPLOY_AZURE.md) | Tutorial deploy ke Azure |
| [docs/DEPLOY_AWS.md](docs/DEPLOY_AWS.md) | Tutorial deploy ke AWS |
| [docs/TODO.md](docs/TODO.md) | Backlog informal |
| [.claude/CLAUDE.md](.claude/CLAUDE.md) | Context untuk AI dev sessions |
