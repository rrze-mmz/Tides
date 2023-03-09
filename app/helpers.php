<?php

use App\Enums\Acl;
use App\Enums\Content;
use App\Models\Asset;
use App\Models\Clip;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function GuzzleHttp\describe_type;

/**
 * Returns poster image relative file path of a clip or default
 *
 * @param  Clip  $clip
 * @return string
 */
function fetchClipPoster(Clip $clip): string
{
    $asset = $clip->assets()->orderBy('width', 'desc')->limit(1)->get()->first();
    return (is_null($asset))
        ? '/images/generic_clip_poster_image.png'
        : "/thumbnails/previews-ng/{$asset->player_preview}";
}

/**
 * Return file dir for a clip based on created date
 *
 * @param  Clip  $clip
 * @return string
 */
function getClipStoragePath(Clip $clip): string
{
    return '/'.Carbon::createFromFormat('Y-m-d', $clip->created_at->format('Y-m-d'))->year.
        '/'.str_pad(Carbon::createFromFormat('Y-m-d', $clip->created_at->format('Y-m-d'))->month, 2, '0', STR_PAD_LEFT).
        '/'.str_pad(Carbon::createFromFormat('Y-m-d', $clip->created_at->format('Y-m-d'))->day, 2, '0', STR_PAD_LEFT).
        "/{$clip->folder_id}/";
}

function getClipSmilFile(Clip $clip, bool $checkFAUTVLinks = true): string
{
    if ($checkFAUTVLinks) {
        return
            config('wowza.stream_url').
            config('wowza.content_path').
            getClipStoragePath($clip).
            'camera.smil/playlist.m3u8';
    } else {
        return
            config('wowza.stream_url').
            config('wowza.content_path').
            getClipStoragePath($clip).
            $clip->getAssetsByType(Content::SMIL)->first()?->original_file_name.
            '/playlist.m3u8';
    }
}

/*
 * Fetch all files in the dropzone with sha1 hash
 * @param bool $ffmpegCheck
 * @return Collection
 */
function fetchDropZoneFiles($ffmpegCheck = true): Collection
{
    /*
     *  Use Storage instead of File Facade. That way although hidden files will be fetched,
     * it's easy to mock and create the test. The hidden files will be filtered.
     */
    return
        collect(Storage::disk('video_dropzone')->files())
            ->filter(function ($file) {
                /*
                 * filter hidden files
                 */
                return $file[0] !== '.';
            })
            ->mapWithKeys(function ($file) use ($ffmpegCheck) {
                return prepareFileForUpload($file, true, $ffmpegCheck);
            })->sortByDesc('date_modified');
}

/**
 * @param $file
 * @param  bool  $isDropZoneFile
 * @param  bool  $ffmpegCheck
 * @return array[]
 */
function prepareFileForUpload($file, bool $isDropZoneFile, bool $ffmpegCheck = true): array
{
    $video = null;
    $mime = null;

    $dateModified = Carbon::createFromTimestamp(now())->format('Y-m-d H:i:s');

    if ($isDropZoneFile) {
        $lastModified = Carbon::createFromTimestamp(Storage::disk('video_dropzone')->lastModified($file))
            ->format('Y-m-d H:i:s');
        $tag = 'dropzone/file';
    } else {
        /*
         * Uploaded file is not allowed to pass to an instance of a job instead save it to disk first
         */
        $path = $file->store('/', 'local');
        $tag = 'single/file';
        $file = $path;
    }

    // Check whether is file is at the moment written at the disk
    if ($isDropZoneFile) {
        if ((Carbon::now()->diffInMinutes($lastModified) > 2 || App::environment('testing')) && $ffmpegCheck) {
            $video = FFMpeg::fromDisk('video_dropzone')->open($file)->getVideoStream();
            $mime = mime_content_type(Storage::disk('video_dropzone')->path($file));
            $dateModified = Carbon::createFromTimestamp(Storage::disk('video_dropzone')
                ->lastModified($file))
                ->format('Y-m-d H:i:s');
        }
    } else {
        $video = FFMpeg::open($file)->getVideoStream();
        $mime = mime_content_type(Storage::disk('local')->path($file));
    }

    return [
        sha1($file) => [
            'tag' => $tag,
            'type' => $mime,
            'video' => ($video !== null)
                ? "{$video->get('width')}x{$video->get('height')}"
                : null,
            'version' => '1',
            'date_modified' => $dateModified,
            'name' => $file,
        ],
    ];
}

/**
 * Returns tailwind menu active link css rule
 *
 * @param  string  $route
 * @return string
 */
function setActiveLink(string $route): string
{
    return (Str::contains(url()->current(), $route)) ? 'active-nav-link opacity-100' : '';
}

/**
 * Generates an LMS token based on the given object (series|clip)
 *
 * @param $obj
 * @param $time
 * @param  string  $client
 * @param  false  $withURL
 * @return string
 */
function getAccessToken($obj, $time, string $client, bool $withURL = false): string
{
    $type = lcfirst(class_basename($obj::class));

    $token = md5($type.$obj->id.$obj->password.request()->ip().$time.$client);

    return ($withURL) ? "/protector/link/{$type}/{$obj->id}/{$token}/{$time}/{$client}" : $token;
}

/**
 * @param  string  $type
 * @return string
 */
function getUrlTokenType(string $type): string
{
    return match ($type) {
        default => abort(404),
        'series', 'course' => 'series',
        'clip' => 'clip',
    };
}

/**
 * @param  string  $client
 * @return string
 */
function getUrlClientType(string $client): string
{
    return match ($client) {
        default => abort(404),
        'studon', 'lms' => Acl::LMS->lower(),
        'password' => Acl::PASSWORD->lower(),
    };
}
/**
 * @param $obj
 * @param $token
 * @param $time
 * @param $client
 * @return void
 */
function setSessionAccessToken($obj, $token, $time, $client): void
{
    $objType = str(class_basename($obj))->lcfirst();

    session()->put([
        "{$objType}_{$obj->id}_token" => $token,
        "{$objType}_{$obj->id}_time" => $time,
        "{$objType}_{$obj->id}_client" => $client,
    ]);
}

/**
 * @param $obj
 * @return bool
 *
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function checkAccessToken($obj): bool
{
    //Type can be either series or clip
    $tokenType = lcfirst(class_basename($obj::class));

    if (session()->exists("{$tokenType}_{$obj->id}_token")) {
        $cookieTokenHash = session()->get("{$tokenType}_{$obj->id}_token");
        $cookieTokenTime = session()->get("{$tokenType}_{$obj->id}_time");
        $cookieTokenClient = session()->get("{$tokenType}_{$obj->id}_client");

        return $cookieTokenHash === getAccessToken($obj, $cookieTokenTime, $cookieTokenClient);
    } elseif ($tokenType === 'clip' && session()->exists("series_{$obj->series->id}_token")) {
        //check whether a series token for this clip exists
        $cookieTokenHash = session()->get("series_{$obj->series->id}_token");
        $cookieTokenTime = session()->get("series_{$obj->series->id}_time");
        $cookieTokenClient = session()->get("series_{$obj->series->id}_client");

        return $cookieTokenHash === getAccessToken($obj->series, $cookieTokenTime, $cookieTokenClient);
    } else {
        return false;
    }
}

/**
 * @param  Asset  $asset
 * @return string
 */
function getFileExtension(Asset $asset): string
{
    return Str::afterLast($asset->original_file_name, '.');
}

/**
 * @param  string  $operation
 * @return int
 */
function opencastWorkflowOperationPercentage(string $operation = ''): int
{
    return match ($operation) {
        'Ingesting external elements' => 2,
        'Inspecting audio and video streams' => 4,
        'Applying access control entries' => 6,
        'Tagging source material for archival' => 8,
        'Tagging metadata catalogs for publication' => 10,
        'Preparing presenter (camera) audio and video work versions' => 12,
        'Preparing presentation (screen) audio and video work version' => 14,
        'Analyze tracks in media package an set control variables' => 16,
        'Normalize audio stream' => 18,
        'Create single-stream video preview' => 21,
        'Create dual-stream video preview' => 22,
        'Generating waveform' => 24,
        'Detecting silence' => 26,
        'Preparing silence detection for preview' => 28,
        'Publish to preview publication channel' => 30,
        'Archive cutting information' => 32,
        'Mark the recording for cutting' => 34,
        'Mark the recording for review' => 36,
        'Sending email to user before holding for edit' => 38,
        'Remove temporary processing artifacts' => 40,
        'Cut the recording according to the edit decision list' => 42,
        'Tagging cutting information for archival' => 44,
        'Resolve the cutting flag' => 50,
        'Resolve the review flag' => 55,
        'Tagging metadata catalogs for archival and publication' => 58,
        'Create static coverimage workflow generates 5 different files with dynamic image' => 62,
        'Create a cover image' => 64,
        'Apply the theme' => 66,
        'Inspecting audio and video streams 2nd loop' => 68,
        'Render watermark into presenter track' => 70,
        'Render watermark into presentation track' => 72,
        'Add coverimage to the combined video' => 74,
        'Concatenate combined track with intro and outro videos' => 76,
        'Concatenate presenter track with intro and outro videos' => 78,
        'Concatenate presentation track with intro and outro videos' => 80,
        'Export audio from trimmed camera file' => 82,
        'Encode presenter for adaptive stream' => 84,
        'Encoding presentation 1080p for multistream player' => 86,
        'Encode combined for adaptive stream' => 88,
        'Change Quality of Layout Video for Final Cut Pro' => 90,
        'Detecting slide transitions in presentation track' => 92,
        'Extracting text from presentation segments' => 94,
        'Tagging media for archival' => 96,
        'Remove final temporary processing artifacts' => 98,
        default => 0,
    };
}

/**
 * @param  string  $opencastRole
 * @return User|string
 */
function findUserByOpencastRole(string $opencastRole): User|string
{
    if (Str::of($opencastRole)->contains('ROLE_USER_')) {
        $username = Str::lower(Str::after($opencastRole, 'ROLE_USER_'));

        return User::search($username)->get()->first();
    } else {
        return $opencastRole;
    }
}

/**
 * @param string $filePath
 * @return string
 */
function getProtectedUrl(string $filePath): string
{
    $filePath = '/'.$filePath;
    $secret = "emsJue5Rtv7";
    $cdn = "https://vp-cdn-balance.rrze.de/media_bu/";
    $hexTime = dechex(time());
    $userIP = (App::environment(['testing', 'local'])) ? env('FAUTV_USER_IP') : $_SERVER['REMOTE_ADDR'];
    $token  = md5($secret.$filePath.$hexTime.$userIP);

    return $cdn.$token.'/'.$hexTime.$filePath;
}
