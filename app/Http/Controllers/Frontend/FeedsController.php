<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Content;
use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Spatie\ArrayToXml\ArrayToXml;

class FeedsController extends Controller
{
    public function series(Series $series)
    {
        \Debugbar::disable();

        $setting = Setting::portal();

        $array = [
            'channel' => [
                'title' => $series->title.' Series ID:'.$series->id,
                'link' => route('frontend.series.feed', $series),
                'language' => $series->clipsLanguageCode(),
                'copyright' => Carbon::now()->year.' Tides Portal',
                'itunes:author' => $series->presenters->first()?->getFullNameAttribute(),
                'description' => $series->description,
                'itunes:type' => 'serial',
                'itunes:owner' => [
                    'itunes:name' => $setting->data['feeds_default_owner_name'],
                    'itunes:email' => $setting->data['feeds_default_owner_email'],
                ],
                'itunes:image' => 'test',
                'itunes:summary' => $series->description,
                'itunes:category' => [
                    '_attributes' => [
                        'text' => 'Education',
                    ],
                ],
                'itunes:keywords' => 'Tides Portal, Tides',
                'itunes:explicit' => 'no',
            ],
        ];

        $count = 0;
        $series->clips
            ->filter(fn ($clip) => $clip->assets()->count() && $clip->is_public)
            ->each(function ($clip) use (&$array, &$count) {
                $array['channel']['__custom:item:'.$count] = [
                    'title' => $clip->title,
                    'itunes:duration' => $clip->assets->first()?->durationToHours(),
                    'enclosure' => [
                        '_attributes' => [
                            'url' => route('assets.download', $clip->assets->first()),
                            'length' => $clip->assets->first()->duration,
                            'type' => ($clip->assets->first()->type === Content::AUDIO()) ? 'audio/mp3' : 'video/mp4',
                        ],
                    ],
                    'guid' => [
                        '_attributes' => [
                            'isPermaLink' => 'false',
                        ],
                        '_value' => 'localhost', //need to be filled with asset link
                    ],
                    'pubDate' => $clip->recording_date,
                    'itunes:explicit' => 'false',
                ];
                $count++;
            });

        $arrayToXml = new ArrayToXml($array, [
            'rootElementName' => 'rss',
            '_attributes' => [
                'version' => '2.0',
                'xmlns:itunes' => 'http://www.itunes.com/dtds/podcast-1.0.dtd',
                'xmlns:content' => 'http://purl.org/rss/1.0/modules/content/',
            ],
        ], true, 'UTF-8');
        $arrayToXml->setDomProperties(['formatOutput' => true]);

        return response($arrayToXml->prettify()->toXml(), 200, ['Content-Type' => 'application/xml']);
    }
}
