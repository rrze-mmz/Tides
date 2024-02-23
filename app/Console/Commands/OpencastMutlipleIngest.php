<?php

namespace App\Console\Commands;

use App\Enums\Content;
use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OpencastMutlipleIngest extends Command
{
    /**
     * The name and signature of the consoleq command.
     *
     * @var string
     */
    protected $signature = 'opencast:multiple-ingest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Multiple ingest opencast files';

    /**
     * Execute the console command.
     */
    public function handle(OpencastService $opencastService)
    {
        Log::info('Starting to ingest multiple opencast files');

        $clip = Clip::find(39198);

        $videoFile = $clip->getAssetsByType(Content::PRESENTER)->get()->first();
        $captionsFile = $clip->getCaptionAsset();
        $opencastMediaPackage = $opencastService->createMediaPackage();
        $opencastMediaPackage = $opencastService->addCatalog($opencastMediaPackage, $clip);
        $opencastMediaPackage = $opencastService->addTrack(
            $opencastMediaPackage,
            'presenter/source',
            $videoFile->path
        );
        $opencastMediaPackage = $opencastService->addTrack(
            $opencastMediaPackage,
            'captions/source+'.$clip->language->code,
            $captionsFile->path
        );
        $opencastService->ingest($opencastMediaPackage, 'edit-subs');
    }
}
