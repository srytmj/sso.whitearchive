# Changelog

Semua perubahan signifikan pada project ini didokumentasikan di sini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]

### Added
- Landing page publik di `/` — menjelaskan SSO Engine dan link ke login/register
- Dashboard superadmin — gate akses role, layout sidebar + topbar
- Dashboard: manajemen OAuth client apps (lihat, tambah, edit, hapus client)
- Dashboard: manajemen user — aktifkan/nonaktifkan, assign role, invite via email
- My Account (`/account`) — info akun, ganti password
- My Account: active sessions & revoke device via `oauth_access_tokens`
- Login page context awareness — banner nama aplikasi saat datang dari OAuth flow
- Forgot password flow via email (Resend) — link reset expired 60 menit, single-use
- Register: field full name opsional, fallback ke username jika kosong

---

## [0.1.0] — 2026-07-20

Implementasi awal SSO Engine — fondasi OAuth2 + autentikasi lokal.

### Added

**Auth Lokal**
- Register user dengan validasi: username, email, password (bcrypt min 8 karakter)
- Login via email/password dengan Laravel session
- Logout: hancurkan session + revoke semua access token aktif
- Role system: `user` dan `superadmin`, di-seed otomatis
- Middleware `CheckUserActive` — user `is_active = false` ditolak di semua endpoint

**OAuth2 Authorization Code + PKCE**
- `GET /oauth/authorize` — titik masuk OAuth2 flow via Laravel Passport
- PKCE (`S256`) wajib — request tanpa `code_challenge` ditolak
- Silent SSO — session aktif langsung redirect dengan auth code tanpa tampilkan login form
- Auto-approve consent untuk first-party client via `skipsAuthorization()`
- `POST /oauth/token` — tukar auth code + code_verifier dengan access token & refresh token

**Token Management**
- Access token TTL: 60 menit
- Refresh token TTL: 30 hari, single-use (rotation on use)
- `RevokeTokenAction` — revoke semua token + refresh token saat logout
- Refresh token flow: `grant_type=refresh_token` via `/oauth/token`

**Userinfo Endpoint**
- `GET /api/user` — return profil user (id, name, username, email, avatar, role)
- Proteksi: `auth:api` + scope `profile:read`
- Response flat tanpa wrapper (sesuai API contract SRS)

**Database**
- Tabel `users` dengan kolom: name, username, email, avatar, role_id, is_active
- Tabel `roles` dengan seeder: user (id=1), superadmin (id=2)
- Read/write split dengan `sticky: true`, database `db_sso`
- Semua tabel OAuth dikelola otomatis oleh Passport

**Security**
- CSRF protection aktif di semua web routes
- Rate limiting: `throttle:5,1` pada `POST /login`, `throttle:3,1` pada `/oauth/token`
- HTTPS enforced via `forceScheme('https')` di production
- Passport keys tidak di-commit ke repository

**Infrastructure**
- `scripts/deploy.sh` — first-time server setup
- `scripts/update.sh` — git pull + migrate + cache clear
- `Makefile` — shortcut `make deploy`, `make update`, `make sync`
- `sync.sh` — auto-sync tech stack dari SRS.md ke CLAUDE.md

---

[Unreleased]: https://github.com/srytmj/sso.whitearchive/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/srytmj/sso.whitearchive/releases/tag/v0.1.0
