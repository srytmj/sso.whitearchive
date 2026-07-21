# TASK-013: Dashboard Superadmin — Gate & Layout

Status: In Review
Priority: Medium
Created: 2026-07-20 21:00
Request: Setup fondasi dashboard superadmin — middleware gate akses role superadmin, layout dashboard, dan halaman index. Ini harus selesai sebelum TASK-014 dan TASK-015 bisa dikerjakan.

Depends on: TASK-002

---

## DEV Response
[DEV mengisi ini]

- [ ] Tambahkan role `superadmin` ke `RoleSeeder` jika belum ada: `{name: "Superadmin", slug: "superadmin"}`
- [ ] Buat `EnsureSuperadmin` middleware di `app/Http/Middleware/EnsureSuperadmin.php`:
  - Cek `$request->user()->role->slug === 'superadmin'`
  - Jika bukan → abort 403
- [ ] Register middleware alias `superadmin` di `bootstrap/app.php`
- [ ] Buat route group `/dashboard` di `routes/web.php` dengan middleware `['auth', 'superadmin']`
- [ ] `GET /dashboard` → `DashboardController@index`
- [ ] Buat `DashboardController` di `app/Http/Controllers/Dashboard/DashboardController.php`
- [ ] Buat `resources/views/layouts/dashboard.blade.php` — layout dengan sidebar navigasi (Applications, Users) dan topbar (nama user, tombol logout)
- [ ] Buat `resources/views/dashboard/index.blade.php` — halaman overview sederhana (jumlah user aktif, jumlah OAuth clients)

---

## QA Response
[QA mengisi ini]

- [ ] GET `/dashboard` tanpa login → redirect ke `/login`
- [ ] GET `/dashboard` dengan login sebagai role `user` → HTTP 403
- [ ] GET `/dashboard` dengan login sebagai role `superadmin` → halaman dashboard tampil
- [ ] Sidebar navigasi tampil: Applications, Users
- [ ] Topbar menampilkan nama user yang sedang login
- [ ] Tombol logout di topbar berfungsi → session hancur, redirect ke `/login`
- [ ] Overview card tampil tanpa error (jumlah user, jumlah clients)
