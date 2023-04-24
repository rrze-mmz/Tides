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
    case MEMBER = 5;
    case AFFILIATE = 6;
    case STUDENT = 7;
    case USER = 8; //treated as local user

    public function lower(): string
    {
        return Str::lower($this->name);
    }
}
