# TASK-009: Login Page Context — App Name Banner

Status: In Review
Priority: Medium
Created: 2026-07-20 20:00
Request: Tambahkan context awareness di halaman login. Jika user datang dari OAuth flow (ada client_id di session), tampilkan banner nama aplikasi yang sedang meminta akses. Jika user akses /login langsung tanpa OAuth context, tampilkan notice yang mengarahkan user untuk masuk via aplikasi client.

---

## DEV Response

- [x] `LoginController::show()` — tambah `resolveClientName()` private method:
  - Baca `session('url.intended')` — Passport menyimpan `/oauth/authorize?...` URL di sini saat redirect ke login
  - Parse `client_id` dari query string via `parse_url` + `parse_str`
  - Query `oauth_clients.name` via `DB::table('oauth_clients')->where('id', $clientId)->value('name')`
  - Return `null` jika tidak ada OAuth context atau client tidak ditemukan
  - Pass `$clientName` (nullable string) ke view
- [x] `resources/views/auth/login.blade.php` — conditional UI:
  - `$clientName` ada → banner biru: *"Anda akan masuk ke [Client Name]. Silakan login untuk melanjutkan."*
  - `$clientName` null → notice amber: *"Untuk masuk ke aplikasi ekosistem whitearchive.id, silakan akses melalui aplikasi yang bersangkutan."*
- [x] Subtitle halaman ikut update: *"Sign in to continue to [Client Name]"* vs *"SSO Engine"*
- [x] Tidak ada perubahan di Service/Action layer

**Notes:**
- Passport tidak menyimpan `authRequest` di session saat guest (hanya disimpan setelah authenticated). Saat redirect ke login, yang tersedia hanya `url.intended` — ini sudah cukup karena `client_id` ada di query string `/oauth/authorize`.
- Jika `client_id` tidak ada di DB atau null → fallback ke notice biasa, tidak error.

---

## QA Response
[QA mengisi ini]

- [ ] GET `/oauth/authorize` (belum login, first-party client "Malas") → redirect ke `/login` → banner "Anda akan masuk ke Malas. Silakan login untuk melanjutkan." tampil
- [ ] GET `/login` langsung → notice amber "Untuk masuk ke aplikasi ekosistem whitearchive.id..." tampil, tidak ada banner app name
- [ ] Setelah login dari OAuth flow → redirect kembali ke `/oauth/authorize` → silent SSO → dapat auth code (flow tidak rusak)
- [ ] Client name yang ditampilkan sesuai dengan nama yang terdaftar di `oauth_clients`
- [ ] Jika `oauth_clients.name` null atau client tidak ditemukan → fallback ke notice biasa, tidak error 500
