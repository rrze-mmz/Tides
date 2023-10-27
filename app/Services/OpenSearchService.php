<?php

namespace App\Services;

use App\Http\Clients\OpenSearchClient;
use App\Models\Setting;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenSearch\ClientBuilder;

class OpenSearchService
{
    private string $type;

    private Collection $response;

    private Setting $openSearchSettings;

    public function __construct(private ClientBuilder $clientBuilder, private OpenSearchClient $client)
    {
        $this->type = '';
        $this->response = collect([]);

        $this->openSearchSettings = Setting::openSearch();
    }

    /*
     * returns OpenSearch cluster health
     */
    public function getHealth(): Collection
    {
        $this->response = collect([
            'releaseId' => [
                'version' => [
                    'number' => 'OpenSearch server not available',
                    'build_type' => 'unknown',
                ],
            ],
            'status' => 'failed',
        ]);
        try {
            $response = $this->client->get('/');

            if (! empty(json_encode((string) $response->getBody(), true))) {
                $this->response->put('releaseId', json_decode((string) $response->getBody(), true))
                    ->put('status', 'pass');
            }
        } catch (GuzzleException $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    public function createIndex(Model $model): Collection
    {
        $this->type = $model->getTable();
        try {
            $params = [
                'index' => $this->openSearchSettings->data['prefix'].$this->type,
                'id' => "{$this->type}_$model->id",
                'body' => $model->toJson(),
            ];
            $this->response = collect($this->clientBuilder->build()->index($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    public function updateIndex(Model $model): Collection
    {
        $this->type = $model->getTable();

        try {
            $params = [
                'index' => $this->openSearchSettings->data['prefix'].$this->type,
                'id' => "{$this->type}_$model->id",
                'body' => [
                    'doc' => $model->toArray(),
                    'doc_as_upsert' => true,
                ],
            ];

            $this->response = collect($this->clientBuilder->build()->update($params));
        } catch (Exception $exception) {
            //avoid error messages if it is running on console command
            if (! App::runningInConsole()) {
                Log::error($exception->getMessage());
            }
        }

        return $this->response;
    }

    public function deleteIndex(Model $model): Collection
    {
        $this->type = $model->getTable();

        try {
            $params = [
                'index' => $this->openSearchSettings->data['prefix'].$this->type,
                'id' => $this->type.'_'.$model->id,
            ];

            $this->response = collect($this->clientBuilder->build()->delete($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    public function fetchDocument($index, $id): Collection
    {
        try {
            $params = [
                'index' => $index,
                'id' => $id,
            ];

            $this->response = collect($this->clientBuilder->build()->getSource($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    /**
     * @throws GuzzleException
     */
    public function deleteIndexes(string $model = ''): Collection
    {
        try {
            $this->response =
                collect($this->client->delete($this->openSearchSettings->data['prefix'].Str::lower($model)));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }

    public function searchIndexes($term, string $index = 'tides_clips'): Collection
    {
        try {
            $params = [
                'index' => $index,
                'body' => [
                    'sort' => [
                        [
                            'updated_at' => [
                                'order' => 'desc',
                            ],
                        ],
                    ],
                    'query' => [
                        'multi_match' => [
                            'query' => "{$term}",
                            'fields' => [
                                'title',
                                'description',
                            ],
                            'fuzziness' => 1,
                            'slop' => 1,
                            'max_expansions' => 1,
                            'prefix_length' => 1,
                        ],
                    ],
                    '_source' => [
                        '*',
                    ],
                    'size' => 100,
                    'highlight' => [
                        'pre_tags' => '<mark>',
                        'post_tags' => '<\/mark>',
                        'fields' => [
                        ],
                    ],
                ],
            ];
            $this->response = collect($this->clientBuilder->build()->search($params));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->response;
    }
}
