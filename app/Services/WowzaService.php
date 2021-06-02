<?php

namespace App\Services;

use App\Http\Clients\WowzaClient;
use App\Models\Clip;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\ArrayToXml\ArrayToXml;

class WowzaService {
    private Response $response;

    public function __construct(private WowzaClient $client)
    {
        $this->response = new Response(200, []);
    }

    /**
     * Check whether Wowza Server is online
     * @return Collection
     * @throws GuzzleException
     */
    public function checkApiConnection(): Collection
    {
        try
        {
            $this->response = $this->client->get('/');
        } catch (GuzzleException $e)
        {
            Log::error($e);
        }

        return collect(json_encode((string)$this->response->getBody(), true));
    }

    /**
     * Generates smil files for wowza streaming
     * @throws \DOMException
     */
    public function createSmilFiles(Clip $clip): void
    {
        $xmlArray = [];

        $xmlArray = [
            'body' => [
                'switch' => []
            ]
        ];

        $xmlArray['body']['switch'] = $clip->assets
                                        ->where('type','=','video')
                                        ->sortByDesc('height')
                                        ->map(function($asset) use ($xmlArray) {
                                               return  $this->createSmilFileArray($asset);
                                            })->toArray();

        $result = new ArrayToXml($xmlArray, [
            'rootElementName' => 'smil',
            '_attributes'     => [
                'title' => 'Clip ID:' . $clip->id
            ]
        ], true, 'UTF-8', '1.0', []);

        $xmlFile = $result->prettify()->toXml();

        Storage::disk('videos')->put(getClipStoragePath($clip).'/camera.smil',$xmlFile);

        Log::info($xmlFile);
    }

    /**
     * @param $asset
     * @return array[]
     */
    public function createSmilFileArray($asset): array
    {
        return  [
            'video' => [
                '_attributes' => [
                    'src'            => 'mp4:' . $asset->original_file_name,
                    'system-bitrate' => $bitrate  = $this->findWowzaAssetBitrate((int)$asset->height),
                    'width'          => $asset->width,
                    'height'         => $asset->height
                ],
                'param1'       => [
                    '_attributes' => [
                        'name'      => 'videoBitrate',
                        'value'     => $bitrate,
                        'valuetype' => 'data',
                    ]
                ],
                'param2'       => [
                    '_attributes' => [
                        'name'      => 'audioBitrate',
                        'value'     => '44100',
                        'valuetype' => 'data',
                    ]
                ],
                'param3'       => [
                    '_attributes' => [
                        'name'      => 'videoCodecId',
                        'value'     => 'avc1.4d401f',
                        'valuetype' => 'data',
                    ]
                ],
                'param4'       => [
                    '_attributes' => [
                        'name'      => 'audioCodecId',
                        'value'     => 'mp4a.40.2',
                        'valuetype' => 'data',
                    ]
                ],
            ]
        ];
    }

    public function findWowzaAssetBitrate($videoPixelHeight): int
    {
        if($videoPixelHeight > 700 && $videoPixelHeight < 800 )
        {
            return  1100000;
        }
        else if($videoPixelHeight > 360 && $videoPixelHeight < 700)
        {
            return  750000;
        }
        else if($videoPixelHeight <= 360)
        {
            return  450000;
        }
        else
        {
            return  1500000;
        }
    }
}
