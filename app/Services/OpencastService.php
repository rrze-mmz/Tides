<?php

namespace App\Services;

use App\Http\Clients\OpencastClient;
use App\Models\Clip;
use App\Models\Series;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\UploadedFile;
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
     *  Return Opencast admin node status
     *
     * @return Collection
     */
    public function getHealth() : Collection
    {
        try {
            $response = $this->client->get('info/health');
        } catch (GuzzleException $exception) {
            Log::error($exception);
            $response = new Response();
        }

        return collect(json_decode((string) $response->getBody(), true));
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
                    'state' => "running",
                    'seriesId'  => $series->opencast_series_id,
                    'count' =>  20,
                    'sort'  => 'DATE_CREATED_DESC'
                ]
            ]);
        } catch (GuzzleException $exception) {
            Log::error($exception);
            $response = new Response();
        }

        return collect(json_decode((string) $response->getBody(), true));
    }

    /**
     * @param Series $series
     * @return ResponseInterface
     */
    public function createSeries(Series $series): ResponseInterface
    {
        try {
            $response =  $this->client->post('api/series', $this->createOpencastSeriesFormData($series));
        } catch (GuzzleException $exception) {
            Log::error($exception);
            $response = new Response();
        }

        return $response;
    }

    public function ingestMediaPackage(Clip $clip, string $videoFile)
    {
        try {
            $response =  $this->client->post(
                'ingest/addMediaPackage/compose-distribute-videoportal-upload',
                $this->ingestMediaPackageFormData($clip, $videoFile)
            );
        } catch (GuzzleException $exception) {
            Log::error($exception);
            $response = new Response();
        }

        return $response;
    }

    /**
     * forms the date for Opencast post request to series api
     *
     * @param Series $series
     * @return Array
     */
    public function createOpencastSeriesFormData(Series $series): Array
    {
        return [
            'headers'  => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
            'form_params' => [
                "metadata" => '[{"flavor": "dublincore/series","title": "EVENTS.EVENTS.DETAILS.CATALOG.EPISODE",
                        "fields": [
                        {
                             "id": "title",
                             "value": "'.$series->title.'",
                         },
                         {
                             "id": "creator",
                             "value": ["'. $series->owner->name.'"],
                         },
                         ]
                    }]',
                "acl" => '[
					{"allow": true,"role": "ROLE_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_ADMIN","action": "write"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "read"},
				    {"allow": true,"role": "ROLE_USER_ADMIN","action": "write"},
			    ]'
            ]
        ];
    }

    public function ingestMediaPackageFormData(Clip $clip, string $file): array
    {
        return [
            'headers'  => [
                'Content-Type: multipart/form-data',
            ],
            'multipart' => [
                [
                    'name'=> 'flavor',
                    'contents' => 'presenter/source'
                ],
                [
                    'name'  => 'title',
                    'contents'  => $clip->title
                ],
                [
                    'name'  => 'isPartOf',
                    'contents'  => $clip->series->opencast_series_id
                ],
                [
                    'name'  =>  'file',
                    'contents'  =>  Storage::disk('videos')->get($file),
                    'filename'  => 'Big_Buck_Bunny.mp4'
                ],
            ]
        ];
    }
}
