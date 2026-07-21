# TASK-015: Dashboard — Users Management & Invite via Email

Status: In Review
Priority: Medium
Created: 2026-07-20 21:00
Request: Buat halaman manajemen user di dashboard superadmin. Superadmin bisa lihat semua user, aktifkan/nonaktifkan akun, assign role, dan invite user baru via email. Invite mengirimkan link satu kali pakai (expired 24 jam) untuk user set password dan complete profile.

Depends on: TASK-013

---

## DEV Response
[DEV mengisi ini]

**User List & Management:**
- [ ] `GET /dashboard/users` → `UserManagementController@index` — list semua user dengan filter dan pagination
- [ ] `PATCH /dashboard/users/{id}/toggle-active` → toggle `is_active` user
- [ ] `PATCH /dashboard/users/{id}/role` → assign role ke user
- [ ] Buat `UserManagementService` di `app/Services/Dashboard/UserManagementService.php`

**Invite Flow:**
- [ ] Buat migration tabel `user_invitations`: `id`, `email`, `token` (unique), `role_id`, `used_at` (nullable), `expires_at`, `created_by` (FK users.id), `timestamps`
- [ ] `GET /dashboard/users/invite` → form invite: input email + pilih role
- [ ] `POST /dashboard/users/invite` → `UserManagementController@invite`:
  - Generate token unik (random 64 char)
  - Simpan ke `user_invitations` dengan `expires_at = now() + 24 jam`
  - Kirim email berisi link: `sso.whitearchive.id/register/invite?token=xxx`
- [ ] Buat `InviteUserAction` di `app/Actions/Dashboard/InviteUserAction.php`
- [ ] Buat `InvitationMail` di `app/Mail/InvitationMail.php` — email dengan link invite
- [ ] `GET /register/invite?token=xxx` → `InviteController@show` — form complete profile (name, username, password)
  - Validasi token: ada di DB, belum dipakai (`used_at IS NULL`), belum expired
  - Jika tidak valid → tampilkan error, tidak bisa proceed
- [ ] `POST /register/invite` → `InviteController@store`:
  - Buat user baru dengan email dari invitation, assign role dari invitation
  - Set `used_at = now()` di invitation record
  - Auto-login, redirect ke `/account`
- [ ] Buat view `resources/views/auth/invite.blade.php` — form complete profile

---

## QA Response
[QA mengisi ini]

- [ ] GET `/dashboard/users` → list semua user dengan nama, email, role, status aktif, tanggal daftar
- [ ] Toggle nonaktifkan user → `is_active = false`, user tidak bisa login
- [ ] Toggle aktifkan kembali → `is_active = true`, user bisa login lagi
- [ ] Assign role ke user → role berubah, tampil di list
- [ ] Superadmin tidak bisa nonaktifkan akun diri sendiri via toggle (safeguard)
- [ ] Invite user → email terkirim ke alamat yang diinput
- [ ] GET `/register/invite?token=valid` → form complete profile tampil, email sudah terisi (read-only)
- [ ] Submit form dengan data valid → user terbuat, auto-login, redirect ke `/account`
- [ ] GET `/register/invite?token=expired` → error "Link undangan telah kedaluwarsa"
- [ ] GET `/register/invite?token=sudah-dipakai` → error "Link undangan sudah pernah digunakan"
- [ ] GET `/register/invite?token=tidak-ada` → error "Link undangan tidak valid"
- [ ] Invite link tidak bisa dipakai dua kali (idempotent)
- [ ] Endpoint dashboard tidak accessible oleh role selain superadmin → 403
