<?php

namespace App\Models\OAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient
{
    public function skipsAuthorization(Authenticatable $user, array $scopes): bool
    {
        return $this->firstParty();
    }
}
