<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\CreateWowzaSmilFile;
use App\Jobs\SendEmail;
use App\Jobs\TransferAssetsJob;
use App\Jobs\TransferOpencastAssets;
use App\Mail\AssetsTransferred;
use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AssetsTransferController extends Controller
{
    use Transferable;

    /**
     * List all available files inside the dropzone folder
     *
     * @param Clip $clip
     * @return View
     */
    public function listDropzoneFiles(Clip $clip): View
    {
        return view('backend.clips.dropzone.listFiles', [
            'clip'  => $clip,
            'files' => fetchDropZoneFiles()
        ]);
    }

    /**
     * Transfer files from dropzone to clip file path
     *
     * @param Clip $clip
     * @param Request $request
     * @return RedirectResponse
     */
    public function transferDropzoneFiles(Clip $clip, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'files'   => 'required|array',
            'files.*' => 'alpha_num',
        ]);

        $this->checkDropzoneFilesForClipUpload($clip, $validated);

        return to_route('clips.edit', $clip);
    }

    /**
     * Lists all opencast processed events
     *
     * @param OpencastService $opencastService
     * @param Clip $clip
     * @return View
     */
    public function listOpencastEvents(OpencastService $opencastService, Clip $clip): View
    {
        $events = $opencastService->getProcessedEventsBySeriesID($clip->series->opencast_series_id);

        return view('backend.clips.opencast.listEvents', [
            'clip'   => $clip,
            'events' => $events->map(function ($event) {
                $event['start'] = Carbon::parse($event['start'])->addHours(2)->format('Y-m-d H:i');
                return $event;
            })
        ]);
    }

    /**
     * @param Clip $clip
     * @param Request $request
     * @param OpencastService $opencastService
     * @return RedirectResponse
     */
    public function transferOpencastFiles(
        Clip            $clip,
        Request         $request,
        OpencastService $opencastService
    ): RedirectResponse {
        $validated = $request->validate([
            'eventID' => 'required|uuid',
        ]);

        $this->checkOpencastAssetsForClipUpload($clip, $validated['eventID'], $opencastService);

        return to_route('clips.edit', $clip);
    }
}
