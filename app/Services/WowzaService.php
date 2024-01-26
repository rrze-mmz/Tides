<?php

namespace App\Services;

use App\Enums\Content;
use App\Http\Clients\WowzaClient;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\Setting;
use DOMException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\ArrayToXml\ArrayToXml;

class WowzaService
{
    private Response $response;

    private Setting $streamingSettings;

    public function __construct(private WowzaClient $client)
    {
        $this->response = new Response(200, []);
        $this->streamingSettings = Setting::streaming();
    }

    /**
     * Check whether Wowza Server is online
     */
    public function getHealth(): Collection
    {
        $response = collect([
            'releaseId' => 'Wowza server not available',
            'status' => 'failed',
        ]);

        try {
            $this->response = $this->client->get('/');
            if (! empty(json_encode((string) $this->response->getBody(), true))) {
                $response->put('releaseId', json_encode((string) $this->response->getBody(), true))
                    ->put('status', 'pass');
            }
        } catch (GuzzleException $e) {
            Log::error($e);
        }

        return $response;
    }

    public function getAllApplications(): Collection
    {
        $response = collect([
            'releaseId' => 'Wowza server not available',
            'status' => 'failed',
        ]);

        try {
            $this->response = $this->client->get('/v2/servers/_defaultServer_/vhosts/_defaultVHost_/applications');
            if (! empty((string) $this->response->getBody())) {
                $response->put('applications', json_encode(simplexml_load_string((string) $this->response->getBody())));
            }
        } catch (GuzzleException $e) {
            Log::error($e);
        }

        return $response;
    }

    /**
     * Generates smil files for wowza streaming
     *
     * @throws DOMException
     */
    public function createSmilFile(Clip $clip): void
    {
        // select all clip video assets, iterate them and create an array for array to xml package
        if ($clip->assets->isNotEmpty()) {
            $this->generateSmilFile($clip, Content::PRESENTER);
            $this->generateSmilFile($clip, Content::PRESENTATION);
            $this->generateSmilFile($clip, Content::COMPOSITE);
        } else {
            Log::info('Clip has no assets');
        }
    }

    /**
     * @throws DOMException
     */
    public function generateSmilFile(Clip $clip, Content $type): void
    {
        $assetsCollection = $clip->getAssetsByType($type)->get();
        if ($assetsCollection->isNotEmpty()) {
            $xmlArray['body']['switch'] = $assetsCollection
                ->sortByDesc('height')
                ->map(function ($asset) {
                    return $this->createSmilFileArray($asset);
                })
                ->toArray();

            $result = new ArrayToXml($xmlArray, [
                'rootElementName' => 'smil',
                '_attributes' => [
                    'title' => 'Clip ID:'.$clip->id,
                ],
            ], true, 'UTF-8', '1.0', []);

            $original_file_name = str($type->name)->lower().'.smil';
            //store the generated file to clip path
            Storage::disk('videos')
                ->put(getClipStoragePath($clip)."/{$original_file_name}", $xmlFile = $result->prettify()->toXml());

            //save or update the smil file in db
            $clip->addAsset([
                'disk' => 'videos',
                'original_file_name' => $original_file_name,
                'type' => Content::SMIL(),
                'guid' => Str::uuid(),
                'path' => getClipStoragePath($clip),
                'duration' => '0',
                'width' => '0',
                'height' => '0',
            ]);
        }
    }

    /**
     * Create a smil array to use for spatie component
     *
     * @return array[]
     */
    public function createSmilFileArray($asset): array
    {
        return [
            'video' => [
                '_attributes' => [
                    'src' => 'mp4:'.$asset->original_file_name,
                    'system-bitrate' => $bitrate = $this->findWowzaAssetBitrate((int) $asset->height),
                    'width' => $asset->width,
                    'height' => $asset->height,
                ],
                'paramVideoBR' => [
                    '_attributes' => [
                        'name' => 'videoBitrate',
                        'value' => $bitrate,
                        'valuetype' => 'data',
                    ],
                ],
                'paramAudioBR' => [
                    '_attributes' => [
                        'name' => 'audioBitrate',
                        'value' => '44100',
                        'valuetype' => 'data',
                    ],
                ],
                'paramVideoCodecID' => [
                    '_attributes' => [
                        'name' => 'videoCodecId',
                        'value' => 'avc1.4d401f',
                        'valuetype' => 'data',
                    ],
                ],
                'paramAudioCodecID' => [
                    '_attributes' => [
                        'name' => 'audioCodecId',
                        'value' => 'mp4a.40.2',
                        'valuetype' => 'data',
                    ],
                ],
            ],
        ];
    }

    /**
     * Get Wowza bitrate value based on asset's height
     */
    public function findWowzaAssetBitrate($videoPixelHeight): int
    {
        return match (true) {
            ($videoPixelHeight > 700 && $videoPixelHeight < 800) => 1100000,
            ($videoPixelHeight > 360 && $videoPixelHeight < 700) => 750000,
            ($videoPixelHeight <= 360) => 450000,
            default => 1500000
        };
    }

    /**
     * Generates a wowza streaming link with secure token settings
     */
    public function vodSecureUrls(Clip $clip): Collection
    {
        $filePaths = collect();
        if ($clip->assets->count() > 0) {
            $clip->getAssetsByType(Content::SMIL)->each(function ($asset) use ($filePaths) {
                $fileType = Str::before($asset->original_file_name, '.smil');
                if (config('wowza.check_fautv_links')) {
                    if (Str::contains($asset->original_file_name, 'composite')) {
                        $asset->original_file_name = 'combined.smil';
                    } elseif (Str::contains($asset->original_file_name, 'presenter')) {
                        $asset->original_file_name = 'camera.smil';
                    } else {
                        $asset->original_file_name = 'slides.smil';
                    }
                }

                $url =
                    config('wowza.stream_url').config('wowza.content_path').
                    $asset->path.$asset->original_file_name.'/playlist.m3u8';
                $wowzaContentPath =
                    $this->streamingSettings->data['content_path'].
                    $asset->path.
                    $asset->original_file_name;
                $secureToken = $this->streamingSettings->data['secure_token'];
                $tokenPrefix = $this->streamingSettings->data['token_prefix'];
                $tokenStartTime = $tokenPrefix.'starttime='.time();
                $tokenEndTime = $tokenPrefix.'endTime='.(time() + 21600);

                $userIP = (App::environment(['testing', 'local'])) ? env('FAUTV_USER_IP') : $_SERVER['REMOTE_ADDR'];
                $hashStr = "{$wowzaContentPath}?{$userIP}&{$secureToken}&{$tokenEndTime}&{$tokenStartTime}";
                $hash = hash('sha256', $hashStr, 1);
                $usableHash = strtr(base64_encode($hash), '+/', '-_');
                $urlWithToken = "{$url}?{$tokenStartTime}&{$tokenEndTime}&{$tokenPrefix}hash={$usableHash}";
                $filePaths->put($fileType, $urlWithToken);
            });
        }

        return $filePaths;
    }
}
