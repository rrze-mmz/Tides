<?php

namespace Tests\Setup;

use App\Http\Clients\ElasticsearchClient;
use Elasticsearch\ClientBuilder;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Ring\Client\MockHandler as RingMockHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;

trait WorksWithElasticsearchClient
{
    use  WithFaker;

    private $stream;

    public function swapElasticsearchGuzzleClient(): MockHandler
    {
        $mockHandler = new MockHandler();

        $client = new ElasticsearchClient([
            'handler' => HandlerStack::create($mockHandler),
        ]);

        $this->app->instance(ElasticsearchClient::class, $client);

        return $mockHandler;
    }

    public function mockClusterHealthResponse(): Response
    {
        return new Response(200, [], json_encode([
            'cluster_name' => 'docker-cluster',
            'status' => 'yellow',
            'timed_out' => false,
            'number_of_nodes' => 1,
            'number_of_data_nodes' => 1,
            'active_primary_shards' => 48,
            'active_shards' => 48,
            'relocating_shards' => 0,
            'initializing_shards' => 0,
            'unassigned_shards' => 4,
            'delayed_unassigned_shards' => 0,
            'number_of_pending_tasks' => 0,
            'number_of_in_flight_fetch' => 0,
            'task_max_waiting_in_queue_millis' => 0,
            'active_shards_percent_as_number' => 92.3076923076923,
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
            '_source' => $model->toArray(),
        ];
    }

    public function closeStream()
    {
        fclose($this->stream);
    }
}
