<?php

function fetchClipPoster($file = null)
{
    return (is_null($file)) ? '/images/generic_clip_poster_image.png' : '/thumbnails/'.$file;
}
