# TASK-010: Landing Page (/)

Status: In Review
Priority: Medium
Created: 2026-07-20 21:00
Request: Buat halaman publik di root URL (/) yang menjelaskan SSO Engine whitearchive.id. Halaman ini yang pertama dilihat visitor — bukan form login, bukan dashboard. Harus accessible tanpa login. Tautan ke /login dan /register.

---

## DEV Response
[DEV mengisi ini]

- [ ] Buat route `GET /` di `routes/web.php` — tidak perlu middleware auth, accessible publik
- [ ] Buat `HomeController` dengan method `index()` — return view `home`
- [ ] Buat `resources/views/home.blade.php` — extends layout baru `layouts/public.blade.php`
- [ ] Buat `resources/views/layouts/public.blade.php` — base layout dengan Tailwind CDN, navbar minimal
- [ ] Konten landing page:
  - Hero: nama produk "SSO Engine — whitearchive.id", tagline singkat
  - Penjelasan singkat: "Identity Provider terpusat untuk ekosistem whitearchive.id menggunakan OAuth2 + PKCE"
  - Feature highlights: Single Sign-On, Self-service register, OAuth2 + PKCE, Secure token management
  - CTA: tombol "Login" → `/login`, tombol "Daftar Sekarang" → `/register`
- [ ] Jika user sudah login → redirect ke `/account` (tidak perlu lihat landing page lagi)

---

## QA Response
[QA mengisi ini]

- [ ] GET `/` tanpa login → halaman landing tampil, tidak redirect ke login
- [ ] GET `/` saat sudah login → redirect ke `/account`
- [ ] Tombol "Login" → mengarah ke `/login`
- [ ] Tombol "Daftar Sekarang" → mengarah ke `/register`
- [ ] Halaman accessible tanpa auth (tidak ada middleware `auth`)
- [ ] Tidak ada data sensitif di-render di halaman ini
