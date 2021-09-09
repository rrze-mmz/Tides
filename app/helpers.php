<?php

use App\Models\Clip;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

/**
 * Returns poster image relative file path of a clip or default
 *
 * @param null $file
 * @return string
 */
function fetchClipPoster($file = null): string
{
    return (is_null($file)) ? '/images/generic_clip_poster_image.png' : '/thumbnails/' . $file;
}

/**
 * Return file dir for a clip based on created date
 *
 * @param Clip $clip
 * @return string
 */
function getClipStoragePath(Clip $clip): string
{
    return '/' . Carbon::createFromFormat('Y-m-d', $clip->created_at->format('Y-m-d'))
            ->year .
        '/' . str_pad(Carbon::createFromFormat('Y-m-d', $clip->created_at->format('Y-m-d'))
            ->month, 2, "0", STR_PAD_LEFT) .
        '/' . str_pad(Carbon::createFromFormat('Y-m-d', $clip->created_at->format('Y-m-d'))
            ->day, 2, "0", STR_PAD_LEFT) . '/'
        . 'TIDES_Clip_ID_' . $clip->id;
}


/*
 * Fetch all files in the dropzone with sha1 hash
 *
 * @return Collection
 */
function fetchDropZoneFiles(): Collection
{
    return collect(Storage::disk('video_dropzone')->files())
        ->mapWithKeys(function ($file) {
            $video = FFMpeg::fromDisk('video_dropzone')->open($file)->getVideoStream();
            $mime = mime_content_type(Storage::disk('video_dropzone')->getAdapter()->applyPathPrefix($file));

            return [sha1($file) => [
                'tag'           => 'dropzone/file',
                'type'          => $mime,
                'video'         => ($video !== null) ? $video->get('width') . 'x' . $video->get('height') : null,
                'version'       => '1',
                'date_modified' => Carbon::createFromTimestamp(Storage::disk('video_dropzone')
                    ->lastModified($file))
                    ->format('Y-m-d H:i:s'),
                'name'          => $file,
            ]
            ];
        })->sortBy('date_modified');
}

/**
 * Returns tailwind menu active link css rule
 *
 * @param string $route
 * @return string
 */
function setActiveLink(string $route): string
{
    return (Str::contains(url()->current(), $route)) ? 'border-b-2' : '';
}

/**
 * Generates a LMS token based on the given object (series|clip)
 *
 * @param $obj
 * @param $time
 * @param false $withURL
 * @return string
 */
function generateLMSToken($obj, $time, bool $withURL = false): string
{
    $type = lcfirst(class_basename($obj::class));

    $token = md5($type . $obj->id . $obj->password . request()->ip() . $time . 'studon');

    return ($withURL) ? '/protector/link/' . $type . '/' . $obj->id . '/' . $token . '/' . $time . '/studon' : $token;
}
