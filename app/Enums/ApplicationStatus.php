<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

enum ApplicationStatus: string
{
    use InvokableCases;

    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
}
