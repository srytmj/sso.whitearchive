<?php

namespace App\Services\Dashboard;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SessionService
{
    public function allOAuthSessions(): Collection
    {
        return DB::table('oauth_access_tokens as t')
            ->join('users as u', 'u.id', '=', 't.user_id')
            ->join('oauth_clients as c', 'c.id', '=', 't.client_id')
            ->where('t.revoked', false)
            ->where('t.expires_at', '>', now())
            ->select(
                't.id',
                't.user_id',
                't.scopes',
                't.created_at',
                't.expires_at',
                'u.name as user_name',
                'u.email as user_email',
                'c.name as client_name',
            )
            ->orderByDesc('t.created_at')
            ->get();
    }

    public function allWebSessions(): Collection
    {
        return DB::table('sessions as s')
            ->join('users as u', 'u.id', '=', 's.user_id')
            ->whereNotNull('s.user_id')
            ->select(
                's.id',
                's.user_id',
                's.ip_address',
                's.user_agent',
                's.last_activity',
                'u.name as user_name',
                'u.email as user_email',
            )
            ->orderByDesc('s.last_activity')
            ->get();
    }

    public function revokeOAuthSession(string $tokenId): void
    {
        DB::table('oauth_access_tokens')
            ->where('id', $tokenId)
            ->update(['revoked' => true]);

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $tokenId)
            ->update(['revoked' => true]);
    }

    public function revokeWebSession(string $sessionId): void
    {
        DB::table('sessions')->where('id', $sessionId)->delete();
    }
}
