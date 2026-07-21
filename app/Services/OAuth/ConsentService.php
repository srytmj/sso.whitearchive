<?php

namespace App\Services\OAuth;

use App\Models\OAuth\Client;
use App\Models\User;

class ConsentService
{
    public function shouldAutoApprove(Client $client, User $user): bool
    {
        return $client->firstParty();
    }
}
