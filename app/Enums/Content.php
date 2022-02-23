<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum Content: string
{
case Presenter = "presenter";
case Presentation = "presentation";
case Composite = "composite";
case Audio = 'audio';
case Slides = 'slides';
case Cc = 'cc';
case Smil = 'smil';

    //lowercase state's name
    public function lower(): string
    {
        return Str::lower($this->name);
    }
    }
