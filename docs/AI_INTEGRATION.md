# SSO Integration Brief — whitearchive.id

> Gunakan file ini sebagai context untuk AI: *"Integrasikan SSO whitearchive.id ke project ini menggunakan panduan berikut."*

---

## Konteks

`sso.whitearchive.id` adalah Central Identity Provider untuk ekosistem whitearchive.id.
Protokol: **OAuth2 Authorization Code + PKCE** (wajib, tidak ada fallback).
Semua aplikasi dalam ekosistem mendelegasikan auth ke sini — tidak perlu bikin login sendiri.

---

## Prasyarat

Superadmin sudah mendaftarkan app ini di `sso.whitearchive.id/dashboard/applications` dan memberikan:

```env
SSO_CLIENT_ID=<uuid>
SSO_CLIENT_SECRET=<secret>
SSO_REDIRECT_URI=https://<domain-app-ini>/auth/callback
SSO_BASE_URL=https://sso.whitearchive.id
```

Tambahkan 4 variabel ini ke `.env` app.

---

## Yang Harus Diimplementasikan

### 1. Tabel users lokal

App membutuhkan tabel `users` lokal dengan kolom dari SSO. **Tidak perlu kolom password** — auth dihandle SSO.

```
sso_id          string unique   # primary key dari SSO, gunakan ini sebagai foreign key
name            string
username        string
email           string unique
avatar          string nullable
role            string          # "user" atau "superadmin"
created_at
updated_at
```

Jika tabel `users` sudah ada dengan kolom `password`, tambahkan `sso_id` sebagai kolom baru (nullable dulu, lalu migrate data jika perlu).

### 2. Dua route auth

```
GET /auth/redirect   → mulai OAuth flow (generate PKCE, redirect ke SSO)
GET /auth/callback   → terima code dari SSO, tukar token, fetch profil, login user
```

### 3. Middleware proteksi

Semua route yang butuh login → redirect ke `/auth/redirect` jika belum ada session.

---

## Implementasi Lengkap (Laravel)

### Migration

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('sso_id')->unique()->after('id');
    $table->string('username')->after('name');
    $table->string('avatar')->nullable()->after('email');
    $table->string('role')->default('user')->after('avatar');
    // Hapus $table->string('password') jika ada dan tidak dibutuhkan
});
```

### Controller

```php
// app/Http/Controllers/Auth/SsoController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SsoController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $codeVerifier = bin2hex(random_bytes(32));
        $codeChallenge = rtrim(
            strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'),
            '='
        );
        $state = bin2hex(random_bytes(16));

        session([
            'sso_code_verifier' => $codeVerifier,
            'sso_state'         => $state,
        ]);

        $query = http_build_query([
            'response_type'         => 'code',
            'client_id'             => config('sso.client_id'),
            'redirect_uri'          => config('sso.redirect_uri'),
            'scope'                 => 'profile:read',
            'code_challenge'        => $codeChallenge,
            'code_challenge_method' => 'S256',
            'state'                 => $state,
        ]);

        return redirect(config('sso.base_url') . '/oauth/authorize?' . $query);
    }

    public function callback(Request $request): RedirectResponse
    {
        abort_if($request->state !== session('sso_state'), 403, 'Invalid state');

        $tokenResponse = Http::asForm()->post(config('sso.base_url') . '/oauth/token', [
            'grant_type'    => 'authorization_code',
            'code'          => $request->code,
            'redirect_uri'  => config('sso.redirect_uri'),
            'client_id'     => config('sso.client_id'),
            'client_secret' => config('sso.client_secret'),
            'code_verifier' => session('sso_code_verifier'),
        ])->json();

        $profile = Http::withToken($tokenResponse['access_token'])
            ->get(config('sso.base_url') . '/api/user')
            ->json();

        $user = User::updateOrCreate(
            ['sso_id' => $profile['id']],
            [
                'name'     => $profile['name'],
                'username' => $profile['username'],
                'email'    => $profile['email'],
                'avatar'   => $profile['avatar'],
                'role'     => $profile['role']['slug'],
            ]
        );

        session()->forget(['sso_code_verifier', 'sso_state']);
        session([
            'sso_access_token'  => $tokenResponse['access_token'],
            'sso_refresh_token' => $tokenResponse['refresh_token'],
        ]);

        Auth::login($user, remember: true);

        return redirect()->intended('/dashboard');
    }
}
```

### Config

```php
// config/sso.php
return [
    'client_id'     => env('SSO_CLIENT_ID'),
    'client_secret' => env('SSO_CLIENT_SECRET'),
    'redirect_uri'  => env('SSO_REDIRECT_URI'),
    'base_url'      => env('SSO_BASE_URL', 'https://sso.whitearchive.id'),
];
```

### Routes

```php
// routes/web.php
Route::get('/auth/redirect', [SsoController::class, 'redirect'])->name('sso.redirect');
Route::get('/auth/callback', [SsoController::class, 'callback'])->name('sso.callback');

// Proteksi semua route yang butuh login:
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', ...);
    // semua route lain
});
```

### Middleware redirect ke SSO (opsional — ganti default Laravel auth redirect)

Di `bootstrap/app.php` atau `AppServiceProvider`, arahkan unauthenticated redirect ke SSO:

```php
// bootstrap/app.php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (AuthenticationException $e, Request $request) {
        return redirect()->route('sso.redirect');
    });
})
```

---

## Refresh Token (opsional tapi direkomendasikan)

Access token expired setelah **60 menit**. Implementasi refresh agar session tidak putus:

```php
// Cek dan refresh token jika expired (misalnya di middleware atau scheduled job)
$tokenResponse = Http::asForm()->post(config('sso.base_url') . '/oauth/token', [
    'grant_type'    => 'refresh_token',
    'refresh_token' => session('sso_refresh_token'),
    'client_id'     => config('sso.client_id'),
    'client_secret' => config('sso.client_secret'),
])->json();

session([
    'sso_access_token'  => $tokenResponse['access_token'],
    'sso_refresh_token' => $tokenResponse['refresh_token'],
]);
```

Refresh token berlaku **30 hari** dan single-use — selalu simpan token baru dari response.

---

## Logout

```php
public function logout(Request $request): RedirectResponse
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Opsional: redirect ke logout SSO agar session SSO juga dihancurkan
    return redirect(config('sso.base_url') . '/logout');
}
```

---

## Data User yang Tersedia

Setelah login, `Auth::user()` return model User lokal yang sudah di-sync dari SSO:

```php
Auth::user()->sso_id    // ID di SSO (gunakan ini jika perlu cross-reference)
Auth::user()->name
Auth::user()->username
Auth::user()->email
Auth::user()->avatar    // URL avatar atau null
Auth::user()->role      // "user" atau "superadmin"
```

Data di-sync setiap kali user login. Untuk update profil, user harus ke `sso.whitearchive.id/account`.

---

## Checklist Implementasi

- [ ] Tambah 4 env var (`SSO_CLIENT_ID`, `SSO_CLIENT_SECRET`, `SSO_REDIRECT_URI`, `SSO_BASE_URL`)
- [ ] Buat/update tabel `users` dengan kolom `sso_id`, `username`, `avatar`, `role`
- [ ] Buat `config/sso.php`
- [ ] Buat `SsoController` dengan method `redirect()` dan `callback()`
- [ ] Daftarkan route `/auth/redirect` dan `/auth/callback`
- [ ] Arahkan unauthenticated request ke `route('sso.redirect')` bukan ke `/login`
- [ ] Test: akses halaman protected → redirect ke SSO → login → balik ke app → session aktif
- [ ] Test: akses lagi setelah login SSO → silent (tidak perlu login ulang)

---

## Error Umum

| Error | Penyebab | Fix |
|-------|----------|-----|
| `invalid_client` | `client_id` atau `client_secret` salah | Cek `.env` |
| `invalid_grant` | `code_verifier` tidak cocok atau code sudah dipakai | Pastikan `code_verifier` disimpan di server-side session |
| 403 Invalid state | `state` di callback tidak cocok session | Jangan simpan `state` di cookie/localStorage |
| `invalid_request` | `code_challenge` tidak ada | PKCE wajib — pastikan `redirect()` generate dan kirim `code_challenge` |
| Token expired (401) | Access token > 60 menit | Implementasi refresh token flow |

---

## Referensi

- API Contract lengkap: `docs/SRS.md`
- Panduan manual (untuk manusia): `docs/INTEGRATION.md`
- SSO Engine repo: `sso.whitearchive.id`
