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
        try
        {
            $response = $this->client->get('info/health');
        } catch (GuzzleException $exception)
        {
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
        try
        {
            $response =  $this->client->post('api/series', $this->createOpencastSeriesFormData($series));
        } catch (GuzzleException $exception)
        {
            Log::error($exception);
            $response = new Response();
        }

        return $response;
    }

    public function ingestMediaPackage(Clip $clip, UploadedFile $videoFile)
    {
        try
        {
            $response =  $this->client->post('ingest/addMediaPackage',
                $this->ingestMediaPackageFormData($clip, $videoFile));
        } catch (GuzzleException $exception)
        {
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

    public function ingestMediaPackageFormData(Clip $clip, UploadedFile $file)
    {
        return [
            'headers'  => [
                'Content-Type: multipart/form-data',
            ],
            'multipart' => [
                [
                    'name'  =>  'file',
                    'contents'  =>  fopen($file,'r'),
                    'filename'  => $file->getClientOriginalName()
                ],
            ]
        ];
    }
}
