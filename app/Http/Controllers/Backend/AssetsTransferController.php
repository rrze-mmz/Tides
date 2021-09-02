<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\CreateWowzaSmilFile;
use App\Jobs\SendEmail;
use App\Jobs\TransferDropzoneFiles;
use App\Mail\VideoUploaded;
use App\Models\Clip;
use App\Services\OpencastService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AssetsTransferController extends Controller
{
    /**
     * List all available files inside the dropzone folder
     *
     * @param Clip $clip
     * @return View
     * @throws AuthorizationException
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
     * @throws AuthorizationException
     */
    public function transferDropzoneFiles(Clip $clip, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'files' => 'required|array'
        ]);

        Bus::chain([
            new TransferDropzoneFiles($clip, fetchDropZoneFiles()->whereIn('hash', $validated['files'])),
            new CreateWowzaSmilFile($clip),
        ])->dispatch();

        //mail can be chained via anonymous function inside the bus but then the test  fails
        Mail::to($clip->owner->email)->queue(new VideoUploaded($clip));

        return redirect($clip->adminPath());
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
        $events = $opencastService->getEventsBySeriesID($clip->series);

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
     */
    public function transferOpencastFiles(Clip $clip, Request $request, OpencastService $opencastService)
    {
        $validated = $request->validate([
            'eventID' => 'required|uuid'
        ]);

        $assets = $opencastService->getAssetsByEventID($validated['eventID']);

        dd($assets->filter(function ($value, $item) {
            return Str::contains($value, 'final');
        }));
    }
}
