<?php

namespace App\Services;

use App\Http\Clients\OpencastClient;
use App\Models\Series;
use Illuminate\Support\Collection;

class OpencastService
{
    private $client;

    public function __construct(OpencastClient $client)
    {
        $this->client = $client;
    }

    /**
     *  Return Opencast admin node status
     *
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHealth() : Collection
    {
        $response = $this->client->get('info/health');

        $body = json_decode((string) $response->getBody(), true);

        return collect($body);
    }

    /**
     * @param Series $series
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createSeries(Series $series): \Psr\Http\Message\ResponseInterface
    {
         return $this->client->post('api/series', $this->createOpencastSeriesFormData($series));
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
}
