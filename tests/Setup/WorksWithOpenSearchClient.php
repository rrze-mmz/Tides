<?php

namespace Tests\Setup;

use App\Http\Clients\OpenSearchClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Ring\Client\MockHandler as RingMockHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use OpenSearch\ClientBuilder;

trait WorksWithOpenSearchClient
{
    use WithFaker;

    private $stream;

    public function swapOpenSearchGuzzleClient(): MockHandler
    {
        $mockHandler = new MockHandler;

        $client = new OpenSearchClient([
            'handler' => HandlerStack::create($mockHandler),
        ]);

        $this->app->instance(OpenSearchClient::class, $client);

        return $mockHandler;
    }

    public function mockClusterHealthResponse(): Response
    {

        return new Response(200, [], json_encode([
            'name' => 'opensearch-node1',
            'cluster_name' => 'opensearch-cluster',
            'cluster_uuid' => 'cluster UUID',
            'version' => [
                'distribution' => 'opensearch',
                'number' => '2.9.0',
                'build_type' => 'tar',
                'build_hash' => 'build_hash',
                'build_date' => '2021-10-07T21:56:19.031608185Z',
                'build_snapshot' => false,
                'lucene_version' => '9.7.0',
                'minimum_wire_compatibility_version' => '7.10.0',
                'minimum_index_compatibility_version' => '7.0.0',
            ],
            'tagline' => 'The OpenSearch Project: https://opensearch.org/',
        ]));
    }

    public function mockClusterNotAvailable(): RequestException
    {
        return new RequestException(
            'Failed to connect to localhost port 8080 after 0 ms: Connection refused ',
            new Request('GET', 'localhost:8080')
        );
    }

    public function startStream(Model $model)
    {
        $body = $this->jsonBody($model);
        $this->stream = fopen('php://memory', 'w+');
        fwrite($this->stream, json_encode($body));
        rewind($this->stream);
    }

    public function jsonBody(Model $model): array
    {
        $type = $model->getTable();

        return [
            '_index' => 'tides_'.$type,
            '_type' => $type,
            '_id' => $type.'_'.$model->id,
            '_version' => 1,
            '_seq_no' => 35,
            '_primary_term' => 1,
            'found' => true,
            'hits' => [
                'hits' => [
                    [
                        '_source' => $model->toArray(),
                    ],
                ],
                'total' => [
                    'value' => '1',
                ],
            ],
        ];
    }

    public function mockSingleDocument(): void
    {
        $mockHandler = new RingMockHandler([
            'status' => 200,
            'transfer_stats' => [
                'total_time' => 100,
            ],
            'body' => $this->stream,
            'effective_url' => 'localhost',
        ]);

        $builder = ClientBuilder::create();
        $builder->setHosts(['localhost']);
        $builder->setHandler($mockHandler);

        $this->app->instance(ClientBuilder::class, $builder);
    }

    public function closeStream()
    {
        fclose($this->stream);
    }
}
