<?php

namespace Tests\Setup;

use App\Http\Clients\StreamingClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithFaker;

trait WorksWithWowzaClient
{
    use WithFaker;

    public function swapWowzaClient(): MockHandler
    {
        $mockHandler = new MockHandler();

        $client = new StreamingClient([
            'handler' => HandlerStack::create($mockHandler),
        ]);

        $this->app->instance(StreamingClient::class, $client);

        return $mockHandler;
    }

    public function mockCheckApiConnection(): Response
    {
        return new Response(200, [], json_encode([
            '0' => 'Wowza Streaming Engine X Perpetual Edition X.X.X.xxx buildYYYVERSION',
        ]));
    }

    public function mockVodSecureUrls(): Response
    {
        $urls = collect([
            'presenter' => 'localhost:9200/__def_inst/videoportal/presenter.smil',
            'presentation' => 'localhost:9200/__def_inst/videoportal/presentation.smil',
            'composite' => 'localhost:9200/__def_inst/videoportal/composite.smil',
        ]);

        return new Response(200, [], json_encode([$urls]));
    }
}
