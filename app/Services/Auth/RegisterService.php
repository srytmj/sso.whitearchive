<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class RegisterService
{
    public function register(array $data): User
    {
        $defaultRole = Role::where('slug', 'user')->firstOrFail();

        return User::create([
            'name' => filled($data['name'] ?? null) ? $data['name'] : $data['username'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $defaultRole->id,
            'is_active' => true,
        ]);
    }
}
