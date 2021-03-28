<?php

use App\Models\Clip;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

function fetchClipPoster($file = null): string
{
    return (is_null($file)) ? '/images/generic_clip_poster_image.png' : '/thumbnails/'.$file;
}

function getClipStoragePath(Clip $clip): string
{
    return '/'.Carbon::createFromFormat('Y-m-d', $clip->created_at)->year.
        '/'.str_pad(Carbon::createFromFormat('Y-m-d', $clip->created_at)->month, 2, "0", STR_PAD_LEFT).
        '/'.str_pad(Carbon::createFromFormat('Y-m-d', $clip->created_at)->day, 2, "0", STR_PAD_LEFT).'/'
        .'TIDES_Clip_ID_'.$clip->id;
}

function fetchDropZoneFiles(): Collection
{
    return collect(Storage::disk('video_dropzone')->files())
        ->map(fn($file) => [
            'date_modified' => Carbon::createFromTimestamp(Storage::disk('video_dropzone')
                ->lastModified($file))
                ->format('Y-m-d H:i:s'),
            'name'          => $file,
            'hash'          => sha1($file),
        ])->sortBy('date_modified');
}
