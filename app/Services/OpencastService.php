<?php

namespace App\Services;

use App\Enums\OpencastWorkflowState;
use App\Http\Clients\OpencastClient;
use App\Models\Clip;
use App\Models\Series;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class OpencastService
{
    private Response $response;

    public function __construct(private OpencastClient $client)
    {
        //initialize an empty response
        $this->response = new Response(200, [], json_encode([]));
    }

    /**
     *  Return a collection of Opencast admin node status
     *
     * @return Collection
     */
    public function getHealth(): Collection
    {
        $response = collect([
            'releaseId'   => 'Opencast server not available',
            'description' => 'unknown',
            'serviceId'   => '127.0.0.1',
            'version'     => null,
            'status'      => 'failed',
        ]);

        try {
            $this->response = $this->client->get('info/health');
            if (!empty(json_decode((string)$this->response->getBody(), true))) {
                $response = collect(json_decode((string)$this->response->getBody(), true));
            }
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        return $response;
    }

    /**
     * Fetch all relevant info for a given series like running, failed workflows, etc.
     *
     * @param Series $series
     * @return Collection
     */
    public function getSeriesInfo(Series $series): Collection
    {
        $opencastSeriesInfo = collect([]);
        if ($health = $this->getHealth()->contains('pass')) {
            $opencastSeriesInfo->prepend($health, 'health');
            $opencastSeriesInfo
                ->prepend($this->getSeriesRunningWorkflows($series), OpencastWorkflowState::RUNNING->lower());
            $opencastSeriesInfo
                ->prepend($this->getFailedEventsBySeries($series), OpencastWorkflowState::FAILED->lower());
        }

        return $opencastSeriesInfo;
    }

    /**
     *  Return opencast running workflows for a series
     *
     * @return Collection
     */
    public function getAllRunningWorkflows(): Collection
    {
        $runningWorkflows = collect([]);
        try {
            $this->response = $this->client->get('workflow/instances.json', [
                'query' => [
                    'state' => OpencastWorkflowState::RUNNING->lower(),
                    'sort'  => 'DATE_CREATED_DESC',
                ],
            ]);
            $runningWorkflows = collect(
                $this->transformRunningWorkflowsResponse(json_decode((string)$this->response->getBody(), true))
            );
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }
        
        return $runningWorkflows;
    }

    /**
     *  Return opencast running workflows for a series
     *
     * @param Series $series
     * @return Collection
     */
    public function getSeriesRunningWorkflows(Series $series): Collection
    {
        $runningWorkflows = collect([]);
        try {
            $this->response = $this->client->get('workflow/instances.json', [
                'query' => [
                    'state'    => OpencastWorkflowState::RUNNING->lower(),
                    'seriesId' => $series->opencast_series_id,
                    'count'    => 20,
                    'sort'     => 'DATE_CREATED_DESC',
                ],
            ]);
            $runningWorkflows = collect(
                $this->transformRunningWorkflowsResponse(json_decode((string)$this->response->getBody(), true))
            );
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        return $runningWorkflows;
    }

    /**
     *  Return opencast events based on status
     *
     * @param $status
     * @return Collection
     */
    public function getEventsByStatus(OpencastWorkflowState $state): Collection
    {
        $runningWorkflows = collect([]);

        try {
            $this->response = $this->client->get('api/events', [
                'query' => [
                    'filter' => 'status:' . $state->value,
                ],
            ]);
            $runningWorkflows = collect((json_decode((string)$this->response->getBody(), true)));
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        return $runningWorkflows;
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
            $this->response = $this->client->post('api/series', $this->createOpencastSeriesFormData($series));
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        return $this->response;
    }

    /**
     * A post request to ingest a video file and start a workflow in Opencast Admin node
     *
     * @param Clip $clip
     * @param string $videoFile
     * @return Response|ResponseInterface
     */
    public function ingestMediaPackage(Clip $clip, string $videoFile): Response|ResponseInterface
    {
        try {
            $this->response = $this->client->post(
                'ingest/addMediaPackage/compose-distribute-videoportal-upload',
                $this->ingestMediaPackageFormData($clip, $videoFile)
            );
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        //TODO
        // Create upload file table, store upload information and re-ingest if failed before deleting

        return $this->response;
    }

    /**
     * Returning a collection with opencast processed and canceled events for a series
     *
     * @param $seriesID
     * @return Collection
     */
    public function getProcessedEventsBySeriesID($seriesID): Collection
    {
        $processedEvents = collect([]);

        try {
            $processed = $this->client->get('api/events', [
                'query' => [
                    'filter' => 'series:' . $seriesID . ',status:' . OpencastWorkflowState::SUCCEEDED(),
                    'sort'   => 'start_date:ASC',
                ],
            ]);
            $canceled = $this->client->get('api/events', [
                'query' => [
                    'filter' => 'series:' . $seriesID . ',status:' . OpencastWorkflowState::STOPPED(),
                    'sort'   => 'start_date:ASC',
                ],
            ]);
            $collection = collect(json_decode((string)$processed->getBody(), true));

            $processedEvents = $collection->merge(collect(json_decode((string)$canceled->getBody(), true)));
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        return $processedEvents;
    }

    /**
     * @param Series $series
     * @return Collection
     */
    public function getFailedEventsBySeries(Series $series): Collection
    {
        $failedEvents = collect([]);
        try {
            $this->response = $this->client->get('api/events', [
                'query' => [
                    'filter' => 'series:' . $series->opencast_series_id . ',status:' . OpencastWorkflowState::FAILED(),
                    'sort'   => 'start_date:ASC',
                ],
            ]);
            $failedEvents = collect(json_decode((string)$this->response->getBody(), true));
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        return $failedEvents;
    }

    /**
     * Fetch event metadata information as a collection
     *
     * @param $eventID
     * @return Collection
     */
    public function getEventByEventID($eventID): Collection
    {
        try {
            $this->response = $this->client->get('api/events/' . $eventID);
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        return collect(json_decode((string)$this->response->getBody(), true));
    }

    /**
     * Fetch all assets for a given event ID as collection
     *
     * @param $eventID
     * @return Collection
     */
    public function getAssetsByEventID($eventID): Collection
    {
        $version = $this->getEventByEventID($eventID)->get('archive_version');

        try {
            $this->response = $this->client->get('assets/episode/' . $eventID);
        } catch (GuzzleException $exception) {
            Log::error($exception);
        }

        // change the xml response to xml object
        $xmlResponse = simplexml_load_string((string)$this->response->getBody());

        //format the response and include the created/modified date
        $dateCreated = (string)$xmlResponse->attributes()->start;

        // isolate the media key from XML Object
        $xmlResponse = (array)$xmlResponse->media;

        // create a collection from the XML Object track value
        $opencastAssets = collect($xmlResponse['track']);

        // return a collection map with uid => delivery tags
        return $opencastAssets->mapWithKeys(function ($element) use ($version, $dateCreated) {
            $extension = match (true) {
                ((string)$element->mimetype === 'audio/mpeg') => '.mp3',
                default => '.m4v',
            };

            return [
                (string)$element->attributes()->id => [
                    'tag'           => (string)$element->attributes()->type,
                    'type'          => (string)$element->mimetype,
                    'video'         => ((string)$element->video->resolution) ?: null,
                    'version'       => $version,
                    'date_modified' => Carbon::createFromTimeString($dateCreated)->toDateTimeString(),
                    'name'          => $element->attributes()->id . $extension,
                ],
            ];
        });
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
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'metadata' => '[{"flavor": "dublincore/series","title": "EVENTS.EVENTS.DETAILS.CATALOG.EPISODE",
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
                'acl'      => '[
					{"allow": true,"role": "ROLE_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_ADMIN","action": "write"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "write"},
			    ]',
                'theme'    => config('opencast.default_theme_id'),
            ],
        ];
    }

    /**
     *  forms the data for Opencast post request to ingest a video file and start a workflow
     *
     * @param Clip $clip
     * @param string $file
     * @return array
     */
    public function ingestMediaPackageFormData(Clip $clip, string $file): array
    {
        return [
            'multipart' => [
                [
                    'name'     => 'flavor',
                    'contents' => 'presenter/source',
                ],
                [
                    'name'     => 'title',
                    'contents' => $clip->title,
                ],
                [
                    'name'     => 'description',
                    'contents' => $clip->id,
                ],
                [
                    'name'     => 'publisher',
                    'contents' => $clip->owner->email,
                ],
                [
                    'name'     => 'isPartOf',
                    'contents' => $clip->series->opencast_series_id,
                ],
                [
                    'name'     => 'file',
                    'contents' => file_get_contents($file),
                    'filename' => basename($file),
                ],
            ],
        ];
    }

    /**
     * Opencast API returns a single json object if one or an array of objects.
     * This method add a single object to an array so that the iteration still works
     *
     * @param array $response
     * @return array
     */
    private function transformRunningWorkflowsResponse(array $response): array
    {
        if (empty($response)) {
            return [];
        }
        if ((int)$response['workflows']['totalCount'] != 1) {
            return $response;
        }

        $response['workflows']['workflow'] = [$response['workflows']['workflow']];

        return $response;
    }
}
