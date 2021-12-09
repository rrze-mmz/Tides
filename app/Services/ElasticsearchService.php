<?php

namespace App\Services;

use App\Http\Clients\ElasticsearchClient;
use Elasticsearch\ClientBuilder;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Log;

class ElasticsearchService
{
    private string $type;
    private Collection $response;
    private Response $guzzleRespose;

    public function __construct(private ClientBuilder $clientBuilder, private ElasticsearchClient $client)
    {
        $this->type = '';
        $this->response = collect([]);
        $this->guzzleRespose = new Response(200, [], json_encode([]));
    }

    /**
     * @return Collection
     */
    public function clusterHealth(): Collection
    {
        $health = collect([]);
        try {
            $this->guzzleRespose = $this->client->get('/_cluster/health');
            $health = collect(json_decode((string)$this->guzzleRespose->getBody(), true));
        } catch (GuzzleException $exception) {
            Log::error($exception->getMessage());
        }

        return $health;
    }

    /**
     * @param Model $model
     * @return Collection
     */
    public function createIndex(Model $model): Collection
    {
        $this->type = $model->getTable();

        try {
            $params = [
                'index' => 'tides_' . $this->type,
                'type'  => $this->type,
                'id'    => $this->type . '_' . $model->id,
                'body'  => $model->toJson()
            ];

            $this->response = collect($this->clientBuilder->build()->index($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    /**
     * @param Model $model
     * @return Collection
     */
    public function updateIndex(Model $model): Collection
    {
        $this->type = $model->getTable();

        try {
            $params = [
                'index' => 'tides_' . $this->type,
                'id'    => $this->type . '_' . $model->id,
                'body'  => [
                    'doc'           => $model->toArray(),
                    'doc_as_upsert' => true,
                ],
            ];

            $this->response = collect($this->clientBuilder->build()->update($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    /**
     * @param Model $model
     * @return Collection
     */
    public function deleteIndex(Model $model): Collection
    {
        $this->type = $model->getTable();

        try {
            $params = [
                'index' => 'tides_' . $this->type,
                'type'  => $this->type,
                'id'    => $this->type . '_' . $model->id,
            ];

            $this->response = collect($this->clientBuilder->build()->delete($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    /**
     * @param $index
     * @param $id
     * @return Collection
     */
    public function fetchDocument($index, $id): Collection
    {
        try {
            $params = [
                'index' => $index,
                'id'    => $id
            ];

            $this->response = collect($this->clientBuilder->build()->getSource($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    /**
     * @param string $model
     * @return Collection
     * @throws GuzzleException
     */
    public function deleteIndexes(string $model = ''): Collection
    {
        try {
            $this->guzzleRespose = $this->client->delete('/tides_' . Str::lower($model));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return collect($this->guzzleRespose);
    }

    /**
     * @param $term
     * @param $index
     * @return Collection
     */
    public function searchIndexes($term, $index = 'tides_clips'): Collection
    {
        try {
            $params = [
                'index' => $index,
                'body'  => [
                    'sort'      => [
                        [
                            'updated_at' => [
                                'order' => 'desc'
                            ]
                        ]
                    ],
                    'query'     => [
                        "multi_match" => [
                            "query"          => "'.$term.'",
                            "fields"         => [
                                "title",
                                "description",
                            ],
                            "fuzziness"      => 1,
                            "slop"           => 1,
                            "max_expansions" => 1,
                            "prefix_length"  => 1
                        ]
                    ],
                    '_source'   => [
                        "*"
                    ],
                    'size'      => 100,
                    'highlight' => [
                        'pre_tags'  => '<mark>',
                        'post_tags' => '<\/mark>',
                        "fields"    => [
                        ]
                    ]
                ]
            ];
            $this->response = collect($this->clientBuilder->build()->search($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }
}
