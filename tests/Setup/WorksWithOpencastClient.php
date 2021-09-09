<?php


namespace Tests\Setup;


use App\Http\Clients\OpencastClient;
use App\Models\Series;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
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
     * Openast single event metadata response
     *
     * @param Series $series
     * @param string $status "SUCCEEDED" | "STOPPED"
     * @return Response
     */
    public function mockEventResponse(Series $series, string $status = 'SUCCEEDED', int $archiveVersion = 4): Response
    {
        return new Response(201, [], json_encode([
                [
                    'identifier'         => Str::uuid(),
                    'creator'            => 'Opencast Project Administrator',
                    'presenter'          => [],
                    'created'            => '2021-05-10T14:21:00Z',
                    'is_part_of'         => $series->opencast_series_id,
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
                    'series'             => $series->title,
                    'has_previews'       => false,
                    'location'           => '',
                    'rightsholder'       => '',
                    'publication_status' => [],
                    'status'             => 'EVENTS.EVENTS.STATUS.PROCESSED',
                ],
            ])
        );
    }

    /**
     * Openast single event metadata response
     *
     * @param Series $series
     * @param string $status "SUCCEEDED" | "STOPPED"
     * @return Response
     */
    public function mockEventByEventID($eventID, string $status = 'SUCCEEDED', int $archiveVersion = 4): Response
    {
        return new Response(201, [], json_encode([
                'identifier'         => $eventID,
                'creator'            => 'Opencast Project Administrator',
                'presenter'          => [],
                'created'            => '2021-05-10T14:21:00Z',
                'is_part_of'         => $this->faker->uuid(),
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

    public function mockSeriesRunningWorkflowsResponse(Series $series, bool $multiple): Response
    {
        $workflows = ($multiple) ? [
            [
                'id'           => 2006754,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'id'     => Str::uuid(),
                    'title'  => $this->faker->sentence,
                    'series' => $series->opencast_series_id,
                ],
                'operations'   => [
                    'operation' =>
                        [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                    [
                        'id'    => 'encode',
                        'state' => 'RUNNING'
                    ]
                ]
            ],
            [
                'id'           => 2006752,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'id'     => Str::uuid(),
                    'title'  => $this->faker->sentence,
                    'series' => $series->opencast_series_id,
                ],
                'operations'   => [
                    'operation' =>
                        [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                    [
                        'id'    => 'encode',
                        'state' => 'RUNNING'
                    ]
                ]
            ],
        ] :
            [
                'id'           => 2006754,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'id'     => Str::uuid(),
                    'title'  => $this->faker->sentence,
                    'series' => $series->opencast_series_id,
                ],
                'operations'   => [
                    'operation' =>
                        [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                    [
                        'id'    => 'encode',
                        'state' => 'RUNNING'
                    ]
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

