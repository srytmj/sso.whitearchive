<?php

namespace App\Providers;

use App\Models\OAuth\Client;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        JsonResource::withoutWrapping();

        Passport::useClientModel(Client::class);

        Passport::tokensCan([
            'profile:read' => 'Read your profile information (name, username, email, avatar, role)',
        ]);

        Passport::tokensExpireIn(now()->addMinutes(60));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        // Auth code TTL defaults to 10 minutes in league/oauth2-server

        Passport::authorizationView('oauth.authorize');
    }
}
