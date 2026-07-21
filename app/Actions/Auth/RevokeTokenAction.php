<?php

namespace App\Actions\Auth;

use App\Models\User;

class RevokeTokenAction
{
    public function execute(User $user): void
    {
        foreach ($user->tokens as $token) {
            $token->revoke();
            $token->refreshToken?->update(['revoked' => true]);
        }
    }
}
