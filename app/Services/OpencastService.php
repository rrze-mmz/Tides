<?php

namespace App\Services;

use App\Http\Clients\OpencastClient;
use App\Models\Clip;
use App\Models\Series;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;

class OpencastService
{

    private OpencastClient $client;

    public function __construct(OpencastClient $client)
    {
        $this->client = $client;
    }

    /**
     *  Return a collection of Opencast admin node status
     *
     * @return Collection
     */
    public function getHealth(): Collection
    {
        try {
            $response = $this->client->get('info/health');
        } catch (GuzzleException $exception) {
            Log::error($exception);
            $response = new Response();
        }

        return collect(json_decode((string)$response->getBody(), true));
    }

    /**
     *  Return opencast running workflows for a series
     * @param Series $series
     * @return Collection
     */
    public function getSeriesRunningWorkflows(Series $series): Collection
    {
//        workflow/instances.json?state=running&seriesId=" . $seriesId . "&count=20&sort=DATE_CREATED_DESC
        try {
            $response = $this->client->get('workflow/instances.json', [
                'query' => [
                    'state'    => "running",
                    'seriesId' => $series->opencast_series_id,
                    'count'    => 20,
                    'sort'     => 'DATE_CREATED_DESC'
                ]
            ]);
        } catch (GuzzleException $exception) {
            Log::error($exception);
            $response = new Response();
        }

        $response = $this->transformRunningWorkflowsResponse(json_decode((string)$response->getBody(), true));

        return collect($response);
    }

    /**
     * A post request to create a series in Opencast Admin node
     *
     * @param Series $series
     * @return Response|ResponseInterface
     */
    public function createSeries(Series $series): Response|ResponseInterface
    {
        try {
            $response = $this->client->post('api/series', $this->createOpencastSeriesFormData($series));
        } catch (GuzzleException $exception) {
            Log::error($exception);
            $response = new Response();
        }

        return $response;
    }

    /**
     * A post request to ingest a video file and start a workflow in Opencast Admin node
     * @param Clip $clip
     * @param string $videoFile
     * @return Response|ResponseInterface
     * @throws FileNotFoundException
     */
    public function ingestMediaPackage(Clip $clip, string $videoFile): Response|ResponseInterface
    {
        try {
            $response = $this->client->post(
                'ingest/addMediaPackage/compose-distribute-videoportal-upload',
                $this->ingestMediaPackageFormData($clip, $videoFile)
            );
        } catch (GuzzleException $exception) {
            Log::error($exception);
            $response = new Response();
        }

        //TODO
        // Create upload file table, store upload information and re-ingest if failed before deleting
        Storage::disk('videos')->delete($videoFile);

        return $response;
    }

    /**
     * forms the data for Opencast post request to series api
     *
     * @param Series $series
     * @return array
     */
    public function createOpencastSeriesFormData(Series $series): array
    {
        return [
            'headers'     => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
            'form_params' => [
                "metadata" => '[{"flavor": "dublincore/series","title": "EVENTS.EVENTS.DETAILS.CATALOG.EPISODE",
                        "fields": [
                        {
                             "id": "title",
                             "value": "' . $series->title . '",
                         },
                         {
                             "id": "creator",
                             "value": ["' . $series->owner->name . '"],
                         },
                         ]
                    }]',
                "acl"      => '[
					{"allow": true,"role": "ROLE_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_ADMIN","action": "write"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "write"},
			    ]',
                "theme" => '601'
            ]
        ];
    }

    /**
     *  forms the data for Opencast post request to ingest a video file and start a workflow
     *
     * @param Clip $clip
     * @param string $file
     * @return array
     * @throws FileNotFoundException
     */
    public function ingestMediaPackageFormData(Clip $clip, string $file): array
    {
        return [
            'headers'   => [
                'Content-Type: multipart/form-data',
            ],
            'multipart' => [
                [
                    'name'     => 'flavor',
                    'contents' => 'presenter/source'
                ],
                [
                    'name'     => 'title',
                    'contents' => $clip->title
                ],
                [
                    'name'     => 'isPartOf',
                    'contents' => $clip->series->opencast_series_id
                ],
                [
                    'name'     => 'file',
                    'contents' => Storage::disk('videos')->get($file),
                    'filename' => basename($file)
                ],
            ]
        ];
    }

    private function transformRunningWorkflowsResponse(array $response)
    {
        if ((int)$response['workflows']['totalCount'] != 1) {
            return $response;
        }

        $response['workflows']['workflow'] = array($response['workflows']['workflow']);

        return $response;
    }
}
