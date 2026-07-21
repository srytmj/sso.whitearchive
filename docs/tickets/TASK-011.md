# TASK-011: My Account — Info Akun & Ganti Password

Status: In Review
Priority: Medium
Created: 2026-07-20 21:00
Request: Buat halaman self-service /account untuk user yang sudah login. Berisi info akun (name, username, email, avatar, role, status verifikasi) dan form ganti password. Ini adalah area personal user, bukan dashboard admin.

Depends on: TASK-003

---

## DEV Response
[DEV mengisi ini]

- [ ] Buat route group `/account` di `routes/web.php` dengan middleware `auth`
- [ ] `GET /account` → `AccountController@show` — halaman utama My Account
- [ ] Buat `AccountController` di `app/Http/Controllers/Account/AccountController.php`
- [ ] Buat `resources/views/account/show.blade.php`:
  - Tampilkan: name, username, email, avatar (placeholder jika null), role badge, status verifikasi email
  - Section ganti password: form current_password, new_password, new_password_confirmation
- [ ] Buat `ChangePasswordAction` di `app/Actions/Account/ChangePasswordAction.php`:
  - Verifikasi `current_password` cocok dengan hash di DB
  - Hash `new_password` baru, update user
  - Jika salah → `ValidationException`
- [ ] `POST /account/password` → `AccountController@changePassword` — delegasi ke `ChangePasswordAction`
- [ ] Validasi: `current_password` required, `new_password` min 8 confirmed, tidak boleh sama dengan current

---

## QA Response
[QA mengisi ini]

- [ ] GET `/account` tanpa login → redirect ke `/login`
- [ ] GET `/account` dengan login → tampil info user yang sedang login (bukan user lain)
- [ ] Semua field info akun tampil: name, username, email, role, status verifikasi
- [ ] Avatar null → tampil placeholder, tidak error
- [ ] Ganti password dengan `current_password` benar dan `new_password` valid → password terupdate, session tetap aktif
- [ ] Ganti password dengan `current_password` salah → error message, password tidak berubah
- [ ] `new_password` sama dengan `current_password` → ditolak dengan pesan yang jelas
- [ ] `new_password` kurang dari 8 karakter → validation error
- [ ] Setelah ganti password → user masih bisa login dengan password baru
