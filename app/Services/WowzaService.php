<?php

namespace App\Services;

use App\Http\Clients\WowzaClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class WowzaService
{

    private Response $response;

    public function __construct(private WowzaClient $client)
    {
        $this->response = new Response(200, []);
    }

    /**
     * Check whether Wowza Server is online
     * @return Collection
     * @throws GuzzleException
     */
    public function checkApiConnection(): Collection
    {
        try {
            $this->response = $this->client->get('/');
        } catch (GuzzleException $e) {
            Log::error($e);
        }

        return collect(json_encode((string)$this->response->getBody(), true));
    }
}
