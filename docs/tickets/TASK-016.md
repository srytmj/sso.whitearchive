# TASK-016: Register — Full Name Opsional (Fallback ke Username)

Status: In Review
Priority: Low
Created: 2026-07-20 21:30
Request: Ubah field "name" di form register menjadi opsional. Jika user tidak mengisi full name, sistem otomatis menggunakan username sebagai name. Perubahan kecil di validasi dan service layer.

Depends on: TASK-003

---

## DEV Response

- [x] `RegisterController`: ubah validasi `name` dari `required` → `nullable|string|max:255` — `app/Http/Controllers/Auth/RegisterController.php:24`
- [x] `RegisterService`: tambah logic `filled($data['name'] ?? null) ? $data['name'] : $data['username']` — `app/Services/Auth/RegisterService.php:16`
- [x] `resources/views/auth/register.blade.php`: label "Full Name" + badge `<span>` "opsional" (pure Tailwind), placeholder "Kosongkan untuk pakai username"
- [x] Sekalian: redirect post-register ke `/account` (user) atau `/dashboard` (superadmin), konsisten dengan post-login — `RegisterController:32-35`

---

## QA Response

- [ ] Register dengan full name diisi → `users.name` tersimpan sesuai input
- [ ] Register dengan full name kosong → `users.name` tersimpan sama dengan `username`
- [ ] Register dengan full name hanya spasi → dianggap kosong, fallback ke username
- [ ] Validasi lain (username, email, password) tidak terpengaruh
