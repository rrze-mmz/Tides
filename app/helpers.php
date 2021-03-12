<?php

function clipPoster($clipPoster = null)
{
    if($clipPoster !== null)
    {
        return $clipPoster;
    }
    return asset('/images/generic_clip_poster_image.png');

}
