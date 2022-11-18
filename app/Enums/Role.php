<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use Illuminate\Support\Str;

enum Role: int
{
    use InvokableCases;

    case SUPERADMIN = 1;
    case ADMIN = 2;
    case MODERATOR = 3;
    case ASSISTANT = 4;
    case USER = 5;

    public function lower(): string
    {
        return Str::lower($this->name);
    }
}
