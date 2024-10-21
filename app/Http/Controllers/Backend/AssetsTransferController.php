<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Backend\Traits\Transferable;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadPodcastAudioFile;
use App\Models\Clip;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\Traits\UploadAssetRequest;
use App\Services\OpencastService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AssetsTransferController extends Controller
{
    use Transferable;

    public function transferSingleAsset(Clip $clip, UploadAssetRequest $request): RedirectResponse
    {
        $sourceDisk = 'local';
        $this->uploadAssets(
            $clip,
            collect(prepareFileForUpload($request->validated(['asset']), false, true)),
            '',
            $sourceDisk
        );

        return to_route('clips.edit', $clip);
    }

    /**
     * List all available files inside the dropzone folder
     */
    public function listDropzoneFiles(Clip $clip): View
    {
        return view('backend.clips.dropzone.listFiles', [
            'clip' => $clip,
            'files' => fetchDropZoneFiles(),
        ]);
    }

    /**
     * Transfer files from dropzone to clip file path
     */
    public function transferDropzoneFiles(Clip $clip, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'files' => 'required|array',
            'files.*' => 'alpha_num',
        ]);

        $this->checkDropzoneFilesForUpload($clip, $validated);

        return to_route('clips.edit', $clip);
    }

    /**
     * Lists all opencast processed events
     */
    public function listOpencastEvents(OpencastService $opencastService, Clip $clip): View
    {
        if (is_null($clip->series->opencast_series_id)) {
            $events = collect();
        } else {
            $events = $opencastService->getProcessedEventsBySeriesID($clip->series->opencast_series_id);
        }

        return view('backend.clips.opencast.listEvents', [
            'clip' => $clip,
            'events' => $events->map(function ($event) {
                $event['start'] = Carbon::parse($event['start'])->addHours(2)->format('Y-m-d H:i');

                return $event;
            }),
        ]);
    }

    public function transferOpencastFiles(
        Clip $clip,
        Request $request,
        OpencastService $opencastService
    ): RedirectResponse {
        $validated = $request->validate([
            'eventID' => 'required|uuid',
        ]);

        $this->checkOpencastAssetsForUpload($clip, $validated['eventID'], $opencastService);

        return to_route('clips.edit', $clip);
    }

    public function transferPodcastAudioFile(
        Podcast $podcast,
        PodcastEpisode $episode,
        UploadPodcastAudioFile $request
    ) {
        $validated = $request->validated();
        $validatedFiles = [
            0 => [
                'video' => false,
                'name' => Str::afterLast($validated['asset'], '/'),
                'filePond' => true,
                'path' => $validated['asset'],
            ],
        ];
        $this->checkFilePondFilesForUpload(model: $episode, validatedFiles: $validatedFiles);

        return to_route('podcasts.episodes.edit', compact('podcast', 'episode'));
    }
}
