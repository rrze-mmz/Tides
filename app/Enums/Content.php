<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum Content: string
{
    case Presenter = "Camera video file";
    case Presentation = "Slides video file";
    case Composite = "Camera and slides video file";
    case Audio = 'Only audio file';
    case Slides = 'Slides file as pdf, ppt';
    case Cc = 'Closed captions file';
    case Smil = 'Smil file';

    //lowercase state's name
    public function lower(): string
    {
        return Str::lower($this->name);
    }
}
