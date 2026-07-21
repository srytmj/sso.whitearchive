# TASK-012: My Account — Active Sessions & Revoke Device

Status: In Review
Priority: Medium
Created: 2026-07-20 21:00
Request: Tambahkan halaman active sessions di /account/sessions. User bisa lihat daftar session/token aktif (device, waktu login, last used) dan mencabut session dari device yang tidak dikenal. Ini memanfaatkan tabel oauth_access_tokens yang sudah ada dari Passport.

Depends on: TASK-011

---

## DEV Response
[DEV mengisi ini]

- [ ] `GET /account/sessions` → `AccountController@sessions`
- [ ] `DELETE /account/sessions/{tokenId}` → `AccountController@revokeSession`
- [ ] Query active sessions: `$user->tokens()->where('revoked', false)->where('expires_at', '>', now())->get()`
- [ ] Tampilkan per token: nama client (dari relasi `client`), `created_at` (login time), `updated_at` (last used), tandai mana "Session ini" (current token jika ada)
- [ ] Buat `RevokeSessionAction` di `app/Actions/Account/RevokeSessionAction.php`:
  - Verifikasi token milik user yang sedang login (jangan bisa revoke token user lain)
  - `$token->revoke()` + revoke refresh tokens terkait
- [ ] Buat `resources/views/account/sessions.blade.php`:
  - List session aktif dengan info client name, login time, last used
  - Tombol "Cabut" per session (kecuali session web saat ini jika bisa diidentifikasi)
  - Tombol "Cabut Semua Session Lain"
- [ ] `DELETE /account/sessions/all` → revoke semua token kecuali yang sedang dipakai

---

## QA Response
[QA mengisi ini]

- [ ] GET `/account/sessions` → tampil daftar token aktif milik user yang login
- [ ] Token expired atau revoked tidak tampil di list
- [ ] Klik "Cabut" pada satu session → token di-revoke, hilang dari list
- [ ] Setelah token di-revoke → GET `/api/user` dengan token tersebut → HTTP 401
- [ ] User tidak bisa revoke token milik user lain (coba kirim tokenId milik user lain → ditolak 403)
- [ ] "Cabut Semua Session Lain" → semua token lain revoked, session web saat ini tetap aktif
- [ ] Halaman tidak accessible tanpa login → redirect ke `/login`
