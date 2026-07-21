# TODO — SSO Engine

Catatan informal, backlog item, dan hal-hal yang perlu diputuskan. Bukan pengganti ticket — kalau sudah cukup jelas, buat TASK di docs/tickets/.

---

## Segera (Pre-Deploy)

- [ ] Runtime verification lokal — test full OAuth flow via HTTP sungguhan (belum dilakukan, semua QA masih static code review)
- [ ] Setup Resend: verifikasi domain `whitearchive.id` di Cloudflare + Resend dashboard, isi `RESEND_API_KEY` di `.env` production
- [ ] Jalankan `php artisan migrate` di server (ada migration baru: `remove_admin_role`)
- [ ] First deploy via `make deploy`

## Setelah Deploy

- [ ] Daftarkan client app pertama (Malas atau Scribe) via dashboard
- [ ] Test SSO end-to-end lintas app di staging/production
- [ ] Verifikasi email invite user berfungsi (Resend live)
- [ ] Verifikasi forgot password email terkirim dan link valid

## Perlu Diputuskan

- Avatar storage: local disk atau S3? (belum diimplementasikan)
- Email verification setelah register: mau diaktifkan atau skip? (kolom `email_verified_at` sudah ada di DB)
- Refresh token: di sisi client app, implementasi auto-refresh atau handle 401 on-demand?

## Backlog (P2/P3)

- [ ] Avatar upload (`/account`)
- [ ] Audit log login events (siapa login kapan, dari IP mana)
- [ ] Email verification setelah register
- [ ] Multi-factor authentication (TOTP)
- [ ] Webhook notifikasi ke client app saat user di-deactivate
- [ ] Social login (Google, GitHub)
- [ ] OpenID Connect Discovery endpoint (`/.well-known/openid-configuration`)

## Catatan

- Roles yang valid: `user` dan `superadmin` saja — role `admin` sudah dihapus (migration + seeder diupdate)
- Ekosistem saat ini: Malas, Scribe — keduanya akan jadi OAuth client
- Untuk integrasi client app baru: lihat `docs/INTEGRATION.md` atau `docs/AI_INTEGRATION.md`
