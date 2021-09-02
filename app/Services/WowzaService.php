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

class WowzaService
{
    private Response $response;

    public function __construct(private WowzaClient $client)
    {
        $this->response = new Response(200, []);
    }

    /**
     * Check whether Wowza Server is online
     *
     * @return Collection
     */
    public function checkApiConnection(): Collection
    {
        try {
            $this->response = $this->client->get('/');
        } catch (GuzzleException $e) {
            Log::error($e);
        }

        return collect(json_encode((string)$this->response->getBody(), true));
    }

    /**
     * Generates smil files for wowza streaming
     *
     * @throws \DOMException
     */
    public function createSmilFile(Clip $clip): void
    {

        $xmlArray = [
            'body' => [
                'switch' => []
            ]
        ];

        // select all clip video assets, itterate them and create an array for array to xml package
        $xmlArray['body']['switch'] = $clip->assets
            ->where('type', '=', 'video')
            ->sortByDesc('height')
            ->map(function ($asset) {
                return $this->createSmilFileArray($asset);
            })
            ->toArray();

        $result = new ArrayToXml($xmlArray, [
            'rootElementName' => 'smil',
            '_attributes'     => [
                'title' => 'Clip ID:' . $clip->id
            ]
        ], true, 'UTF-8', '1.0', []);


        //store the generated file to clip path
        Storage::disk('videos')
            ->put($assetPath = getClipStoragePath($clip) . '/camera.smil', $xmlFile = $result->prettify()->toXml());

        //save or update the smil file in db
        $clip->addAsset([
            'disk'               => 'videos',
            'original_file_name' => 'camera.smil',
            'type'               => 'smil',
            'path'               => $assetPath,
            'duration'           => '0',
            'width'              => '0',
            'height'             => '0',
        ]);

        Log::info($xmlFile);
    }

    /**
     * Create a smil array to use for spatie component
     *
     * @param $asset
     * @return array[]
     */
    public function createSmilFileArray($asset): array
    {
        return [
            'video' => [
                '_attributes'       => [
                    'src'            => 'mp4:' . $asset->original_file_name,
                    'system-bitrate' => $bitrate = $this->findWowzaAssetBitrate((int)$asset->height),
                    'width'          => $asset->width,
                    'height'         => $asset->height
                ],
                'paramVideoBR'      => [
                    '_attributes' => [
                        'name'      => 'videoBitrate',
                        'value'     => $bitrate,
                        'valuetype' => 'data',
                    ]
                ],
                'paramAudioBR'      => [
                    '_attributes' => [
                        'name'      => 'audioBitrate',
                        'value'     => '44100',
                        'valuetype' => 'data',
                    ]
                ],
                'paramVideoCodecID' => [
                    '_attributes' => [
                        'name'      => 'videoCodecId',
                        'value'     => 'avc1.4d401f',
                        'valuetype' => 'data',
                    ]
                ],
                'paramAudioCodecID' => [
                    '_attributes' => [
                        'name'      => 'audioCodecId',
                        'value'     => 'mp4a.40.2',
                        'valuetype' => 'data',
                    ]
                ],
            ]
        ];
    }

    /**
     * Get Wowza bitrate value based on asset height
     *
     * @param $videoPixelHeight
     * @return int
     */
    public function findWowzaAssetBitrate($videoPixelHeight): int
    {
        if ($videoPixelHeight > 700 && $videoPixelHeight < 800) {
            return 1100000;
        } elseif ($videoPixelHeight > 360 && $videoPixelHeight < 700) {
            return 750000;
        } elseif ($videoPixelHeight <= 360) {
            return 450000;
        } else {
            return 1500000;
        }
    }
}
