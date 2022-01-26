<?php


namespace Tests\Setup;


use App\Http\Clients\OpencastClient;
use App\Models\Series;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use PHPUnit\Util\Xml;
use Spatie\ArrayToXml\ArrayToXml;


trait WorksWithOpencastClient
{
    use WithFaker;

    public function swapOpencastClient(): MockHandler
    {
        $mockHandler = new MockHandler();

        $client = new OpencastClient([
            'handler' => HandlerStack::create($mockHandler)
        ]);

        $this->app->instance(OpencastClient::class, $client);

        return $mockHandler;
    }

    public function mockHealthResponse(): Response
    {
        return new Response(200, [], json_encode([
            "releaseId"   => "8.10.0",
            "description" => "Opencast node's health status",
            "serviceId"   => "http://localhost:8080",
            "version"     => "1",
            "status"      => "pass",
        ]));
    }

    public function mockServerNotAvailable(): RequestException
    {
        return new RequestException(
            'Failed to connect to localhost port 8080 after 0 ms: Connection refused ',
            new Request('GET', 'localhost:8080')
        );
    }

    public function mockCreateSeriesResponse(): Response
    {
        return new Response(201, [
            'Location' => [
                '0' => 'http://localhost:8080/api/series/' . Str::uuid()
            ]
        ]);
    }

    public function mockIngestMediaPackageResponse(): Response
    {
        return new Response(200, [], json_encode([
            new Xml()
        ]));
    }

    /**
     * Opencast single event metadata response
     *
     * @param Series $series
     * @param string $state
     * @param string $status "SUCCEEDED" | "STOPPED"
     * @param int $archiveVersion
     * @param string $identifier
     * @return Response
     */
    public function mockEventResponse(Series $series,
                                      string $state = 'SUCCEEDED',
                                      string $status = 'EVENTS.EVENTS.STATUS.PROCESSED',
                                      int    $archiveVersion = 4,
                                      string $identifier = 'a131d2e2-9de2-40cb-9716-af9824055f4a'): Response
    {
        return new Response(201, [], json_encode([
                [
                    'identifier'         => $identifier,
                    'creator'            => 'Opencast Project Administrator',
                    'presenter'          => [],
                    'created'            => Carbon::now()->toIso8601ZuluString(),
                    'is_part_of'         => $series->opencast_series_id,
                    'subjects'           => [],
                    'start'              => Carbon::now()->addMinutes(1)->toIso8601ZuluString(),
                    'description'        => '1', // A clip ID that belongs to the series
                    'language'           => '',
                    'source'             => '',
                    'title'              => 'Processed event',
                    'processing_state'   => $state,
                    'license'            => '',
                    'archive_version'    => $archiveVersion,
                    'contributor'        => [],
                    'series'             => $series->title,
                    'has_previews'       => false,
                    'location'           => '',
                    'rightsholder'       => '',
                    'publication_status' => [],
                    'status'             => $status,
                ],
            ])
        );
    }

    /**
     * Opencast single event metadata response
     *
     * @param $eventID
     * @param string $status "SUCCEEDED" | "STOPPED"
     * @param int $archiveVersion
     * @param string $seriesID
     * @return Response
     */
    public function mockEventByEventID($eventID,
                                       string $status = 'SUCCEEDED',
                                       int $archiveVersion = 4,
                                       string $seriesID = 'a131d2e2-9de2-40cb-9716-af9824055f23'): Response
    {
        return new Response(201, [], json_encode([
                'identifier'         => $eventID,
                'creator'            => 'Opencast Project Administrator',
                'presenter'          => [],
                'created'            => '2021-05-10T14:21:00Z',
                'is_part_of'         => $seriesID,
                'subjects'           => [],
                'start'              => '2021-05-10T14:21:21Z',
                'description'        => '',
                'language'           => '',
                'source'             => '',
                'title'              => 'Processed event',
                'processing_state'   => $status,
                'license'            => '',
                'archive_version'    => $archiveVersion,
                'contributor'        => [],
                'series'             => $this->faker->sentence(10),
                'has_previews'       => false,
                'location'           => '',
                'rightsholder'       => '',
                'publication_status' => [],
                'status'             => 'EVENTS.EVENTS.STATUS.PROCESSED',
            ])
        );
    }

    public function mockEventAssets($videoHDAssetID, $audioAssetID): Response
    {
        $output = new ArrayToXml([
            'media' => [
                'track' => [
                    [
                        '_attributes' => [
                            'id'   => $this->faker->uuid(),
                            'type' => 'source/presenter',
                        ],
                        'mimetype'    => 'video/mp4',
                        'video'       => [
                            'resolution' => '1280x720']
                    ],
                    [
                        '_attributes' => [
                            'id'   => $videoHDAssetID,
                            'type' => 'final/presenter',
                        ],
                        'mimetype'    => 'video/mp4',
                        'video'       => [
                            'resolution' => '1280x720']
                    ],
                    [
                        '_attributes' => [
                            'id'   => $audioAssetID,
                            'type' => 'final/soundfile',
                        ],
                        'mimetype'    => 'audio/mpeg',
                    ]
                ],
            ]],
            ['rootElementName' => 'mediapackage',
             '_attributes'     => [
                 'start' => Carbon::now()->toIso8601ZuluString(),
             ]], true, 'UTF-8', '1.0', []);

        return new Response(200, [], (
        $output->toXml()
        ));
    }

    public function mockSeriesRunningWorkflowsResponse(Series $series, bool $multiple = false): Response
    {
        $workflows = ($multiple) ? [
            [
                'id'           => 2006754,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'duration' => 3048683,
                    'id'       => Str::uuid(),
                    'start'    => Carbon::now()->addMinutes(1)->toIso8601ZuluString(),
                    'title'    => $this->faker->sentence,
                    'series'   => $series->opencast_series_id,
                    'creators' => [
                        'creator' => 'Dr. John Doe'
                    ]
                ],
                'operations'   => [
                    'operation' => [
                        0 => [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                        1 => [
                            'id'          => 'encode',
                            'state'       => 'RUNNING',
                            'description' => 'Encode presenter for adaptive stream',
                        ]
                    ],
                ]
            ],
            [
                'id'           => 2006752,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'duration' => 3048683,
                    'id'       => Str::uuid(),
                    'start'    => Carbon::now()->addMinutes(1)->toIso8601ZuluString(),
                    'title'    => $this->faker->sentence,
                    'series'   => $series->opencast_series_id,
                ],
                'operations'   => [
                    'operation' => [
                        0 => [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                        1 => [
                            'id'          => 'encode',
                            'state'       => 'RUNNING',
                            'description' => 'Encode presenter for adaptive stream',
                        ]
                    ],
                ]
            ],
        ] :
            [
                'id'           => 2006754,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'duration' => 3048683,
                    'id'       => Str::uuid(),
                    'start'    => Carbon::now()->addMinutes(1)->toIso8601ZuluString(),
                    'title'    => $this->faker->sentence,
                    'series'   => $series->opencast_series_id,
                    'creators' => [
                        'creator' => 'Dr. John Doe'
                    ]
                ],
                'operations'   => [
                    'operation' => [
                        0 => [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                        1 => [
                            'id'          => 'encode',
                            'state'       => 'RUNNING',
                            'description' => 'Encode presenter for adaptive stream',
                        ]
                    ],
                ]
            ];

        return new Response(201, [], json_encode([
            'workflows' => [
                'startPage'  => 0,
                'count'      => 20,
                'searchTime' => 2,
                'totalCount' => $multiple ? 2 : 1,
                'workflow'   => $workflows
            ]
        ]));
    }
}

