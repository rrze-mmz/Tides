<?php

use App\Models\Clip;
use Illuminate\Support\Carbon;

function fetchClipPoster($file = null)
{
    return (is_null($file)) ? '/images/generic_clip_poster_image.png' : '/thumbnails/'.$file;
}

function getClipStoragePath(Clip $clip)
{
    return '/'.Carbon::createFromFormat('Y-m-d', $clip->created_at)->year.
            '/'.str_pad(Carbon::createFromFormat('Y-m-d', $clip->created_at)->month, 2, "0", STR_PAD_LEFT).
            '/'.str_pad(Carbon::createFromFormat('Y-m-d', $clip->created_at)->day, 2, "0", STR_PAD_LEFT).'/'
            .'TIDES_Clip_ID_'.$clip->id;
}


//$savedName  = Carbon::createFromFormat('Y-m-d', $clip->created_at)->format('Ymd').
//    '-'.$clip->slug.
//    '.'.Str::of($file->getClientOriginalName())->after('.');
