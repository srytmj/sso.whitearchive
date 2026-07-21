# TASK-017: Forgot Password via Email (Resend)

Status: In Review
Priority: Medium
Created: 2026-07-20 21:30
Request: Implementasikan forgot password flow menggunakan Laravel built-in password reset + Resend sebagai email driver. User input email → dapat link reset via email → klik link → set password baru. Link expired 60 menit, satu kali pakai.

---

## DEV Response

**Setup Resend:**
- [x] `composer require resend/resend-laravel` — installed, package:discover DONE
- [x] `.env.example` diupdate: `MAIL_MAILER=resend`, `MAIL_FROM_ADDRESS=noreply@whitearchive.id`, `MAIL_FROM_NAME="SSO whitearchive.id"`, `RESEND_API_KEY=re_xxx`
- [ ] Verifikasi domain `whitearchive.id` di Resend dashboard (DNS record via Cloudflare) — runtime step, dilakukan di server

**Password Reset Flow (Laravel built-in):**
- [x] Migration `password_reset_tokens` sudah ada (Laravel default) — confirmed via `config/auth.php:103`
- [x] `ForgotPasswordController` — `show()` GET /forgot-password, `store()` POST /forgot-password via `Password::sendResetLink()` — `app/Http/Controllers/Auth/ForgotPasswordController.php`
- [x] `ResetPasswordController` — `show()` GET /reset-password?token&email, `store()` POST /reset-password via `Password::reset()` + event `PasswordReset` — `app/Http/Controllers/Auth/ResetPasswordController.php`
- [x] Routes di `routes/web.php` (dalam group `guest` middleware):
  - `GET /forgot-password` → `password.request`
  - `POST /forgot-password` → `password.email` (throttle:3,1)
  - `GET /reset-password` → `password.reset`
  - `POST /reset-password` → `password.update`
- [x] `resources/views/auth/forgot-password.blade.php` — form email + success callout via `session('status')`
- [x] `resources/views/auth/reset-password.blade.php` — form password + password_confirmation, token + email hidden
- [x] Link "Lupa password?" di `login.blade.php` → route `password.request`
- [x] `config/auth.php` `passwords.users.expire = 60` — sudah 60 (Laravel default)
- [x] Throttle `throttle:3,1` pada POST `/forgot-password`

**Notes:**
- `Password::reset()` di `ResetPasswordController::store()` menggunakan `$user->forceFill(['password' => $password])` — bypass mutator hashing agar tidak double-hash. Model cast `'password' => 'hashed'` tidak berlaku di `forceFill`, jadi password di-hash manual via `Hash` tidak diperlukan karena `Password::reset()` menerima raw string dan Laravel internal memanggil `$user->save()` setelah closure.
- Wait — koreksi: `forceFill` melewati fillable guard tapi TIDAK melewati model cast. Cast `'password' => 'hashed'` tetap berlaku saat `$user->save()`. Jadi tidak perlu `Hash::make()`. ✓
- `RESEND_API_KEY` harus ada di `.env` production sebelum forgot password bisa di-test runtime.

---

## QA Response

- [ ] GET `/forgot-password` → form input email tampil
- [ ] POST `/forgot-password` dengan email terdaftar → pesan sukses tampil, email reset terkirim ke inbox
- [ ] POST `/forgot-password` dengan email tidak terdaftar → pesan sukses tampil juga (security: tidak reveal apakah email ada di DB)
- [ ] POST `/forgot-password` lebih dari 3x dalam 1 menit → HTTP 429
- [ ] Klik link reset di email → GET `/reset-password?token=xxx&email=xxx` → form password baru tampil
- [ ] Submit password baru yang valid → password terupdate, redirect ke `/login` dengan pesan sukses
- [ ] Login dengan password baru → berhasil
- [ ] Login dengan password lama setelah reset → gagal
- [ ] Gunakan link reset yang sama dua kali → error "token sudah tidak valid"
- [ ] Gunakan link reset setelah 60 menit → error "token sudah kedaluwarsa"
- [ ] Link "Lupa password?" di halaman login → mengarah ke `/forgot-password`
- [ ] Email yang diterima: from address `noreply@whitearchive.id`, subject jelas, link valid
