<?php

namespace App\Services\Dashboard;

use App\Models\OAuth\Client;
use Illuminate\Support\Collection;
use Laravel\Passport\ClientRepository;

class ApplicationService
{
    public function __construct(private readonly ClientRepository $clients) {}

    public function list(): Collection
    {
        return Client::orderByDesc('created_at')->get();
    }

    public function create(array $data): Client
    {
        return $this->clients->createAuthorizationCodeGrantClient(
            name: $data['name'],
            redirectUris: [$data['redirect_uri']],
            confidential: true,
        );
    }

    public function update(Client $client, array $data): void
    {
        $this->clients->update($client, $data['name'], [$data['redirect_uri']]);
    }

    public function delete(Client $client): void
    {
        foreach ($client->tokens as $token) {
            $token->revoke();
            $token->refreshToken?->update(['revoked' => true]);
        }

        $this->clients->delete($client);
    }
}
