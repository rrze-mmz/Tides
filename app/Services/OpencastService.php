<?php

namespace App\Services;

use App\Http\Clients\OpencastClient;
use Illuminate\Support\Collection;

class OpencastService
{
    private $client;

    public function __construct(OpencastClient $client)
    {
        $this->client = $client;
    }

    public function getHealth() : Collection
    {
        $response = $this->client->get('info/health');

        $body = json_decode((string) $response->getBody(), true);

        return collect($body);

    }
}
