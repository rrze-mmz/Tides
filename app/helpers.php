<?php

function clipPoster($clipPoster = null)
{
    if($clipPoster !== null)
    {
        return $clipPoster;
    }
    return 'https://via.placeholder.com/1280x720.png?text=No+Media+in+clip';

}
