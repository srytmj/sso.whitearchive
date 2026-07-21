<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginService
{
    /**
     * @throws ValidationException
     */
    public function attempt(array $credentials, bool $remember = false): void
    {
        $login = $credentials['email'];

        // Determine if input is email or username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $login, 'password' => $credentials['password']], $remember)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['Akun Anda tidak aktif.'],
            ]);
        }
    }
}
