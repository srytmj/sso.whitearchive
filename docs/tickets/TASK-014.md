# TASK-014: Dashboard — Applications (OAuth Client Management)

Status: In Review
Priority: Medium
Created: 2026-07-20 21:00
Request: Buat halaman manajemen OAuth client apps di dashboard superadmin. Superadmin bisa lihat semua registered client, tambah client baru, lihat client_id & client_secret, edit nama/redirect URI, dan hapus/revoke client — semua tanpa perlu tinker atau database GUI.

Depends on: TASK-013

---

## DEV Response
[DEV mengisi ini]

- [ ] `GET /dashboard/applications` → `ApplicationController@index` — list semua OAuth clients
- [ ] `GET /dashboard/applications/create` → `ApplicationController@create` — form tambah client
- [ ] `POST /dashboard/applications` → `ApplicationController@store` — buat client baru via Passport
- [ ] `GET /dashboard/applications/{id}` → `ApplicationController@show` — detail client (credentials)
- [ ] `PATCH /dashboard/applications/{id}` → `ApplicationController@update` — edit nama/redirect URI
- [ ] `DELETE /dashboard/applications/{id}` → `ApplicationController@destroy` — hapus client + revoke semua token
- [ ] Buat `ApplicationService` di `app/Services/Dashboard/ApplicationService.php`:
  - `list()`: query `oauth_clients` via Passport model
  - `create(array $data)`: gunakan Passport's `ClientRepository::create()`
  - `update(Client $client, array $data)`: update nama dan redirect URI
  - `delete(Client $client)`: revoke semua active tokens client ini, hapus client
- [ ] Buat `resources/views/dashboard/applications/` — index, create, show (dengan reveal client_secret sekali)
- [ ] Client secret hanya ditampilkan **sekali** saat client baru dibuat — setelah itu tidak bisa dilihat lagi (tampilkan warning ini di UI)
- [ ] Form tambah client: name (required), redirect_uri (required, valid URL)

---

## QA Response
[QA mengisi ini]

- [ ] GET `/dashboard/applications` → list semua OAuth clients tampil (nama, client_id, created_at)
- [ ] Tambah client baru dengan nama dan redirect_uri valid → client terbuat, halaman show dengan client_secret tampil sekali
- [ ] Refresh halaman show setelah client dibuat → client_secret tidak tampil lagi
- [ ] Edit nama client → perubahan tersimpan dan tampil di list
- [ ] Edit redirect_uri dengan URL tidak valid → validation error
- [ ] Hapus client → client hilang dari list, token yang terkait client ini di-revoke
- [ ] Endpoint ini tidak accessible oleh role selain superadmin → 403
- [ ] `client_id` di list tidak bisa di-copy-paste sebagai secret (hanya informatif)
