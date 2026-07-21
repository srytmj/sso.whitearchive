# TODO — SSO Engine

Catatan informal, backlog item, dan hal-hal yang perlu diputuskan. Bukan pengganti ticket — kalau sudah cukup jelas, buat TASK di docs/tickets/.

## Segera

- [ ] Setup Laravel project baru di root repo
- [ ] Install Laravel Passport
- [ ] Config database read/write split (`db_sso`)
- [ ] Migrations: users, roles
- [ ] Login & register flow (Blade)
- [ ] OAuth2 Authorization Code + PKCE endpoint
- [ ] GET /api/user endpoint

## Perlu Diputuskan

- Default role saat register: "user" — konfirmasi slug yang dipakai (`user` vs `member`)
- Avatar storage: local disk atau S3? (P1, belum urgent)
- Deploy target: EC2 atau VPS lain? Perlu final konfirmasi

## Nanti (P1/P2)

- [ ] Password reset via email
- [ ] Avatar upload
- [ ] Admin panel sederhana untuk manage OAuth clients
- [ ] Audit log login events
- [ ] MFA (TOTP)

## Catatan

- Ekosistem White Archive saat ini punya app: Malas, Scribe — keduanya akan jadi OAuth client consumer dari SSO ini
- SSO ini tidak perlu tahu detail bisnis dari masing-masing app
