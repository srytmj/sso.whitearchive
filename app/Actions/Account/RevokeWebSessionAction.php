<?php

namespace App\Actions\Account;

use App\Models\User;
use App\Services\Dashboard\SessionService;
use Illuminate\Support\Facades\DB;

class RevokeWebSessionAction
{
    public function __construct(private readonly SessionService $sessionService) {}

    public function execute(User $user, string $sessionId): void
    {
        $owner = DB::table('sessions')->where('id', $sessionId)->value('user_id');

        if ((string) $owner !== (string) $user->id) {
            abort(403);
        }

        $this->sessionService->revokeWebSession($sessionId);
    }

    public function revokeAllExcept(User $user, string $currentSessionId): void
    {
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();
    }
}
