<?php

namespace App\Actions\Account;

use App\Models\User;
use Laravel\Passport\Token;

class RevokeSessionAction
{
    public function execute(User $user, string $tokenId): void
    {
        /** @var Token|null $token */
        $token = $user->tokens()->where('id', $tokenId)->first();

        if (! $token) {
            abort(403);
        }

        $token->revoke();
        $token->refreshToken?->update(['revoked' => true]);
    }

    public function revokeAll(User $user): void
    {
        foreach ($user->tokens()->where('revoked', false)->get() as $token) {
            $token->revoke();
            $token->refreshToken?->update(['revoked' => true]);
        }
    }
}
