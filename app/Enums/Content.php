<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

enum Content: int
{
    use InvokableCases;

    case PRESENTER = 1;
    case PRESENTATION = 2;
    case COMPOSITE = 3;
    case AUDIO = 4;
    case SLIDES = 5;
    case CC = 6;
    case SMIL = 7;
}
