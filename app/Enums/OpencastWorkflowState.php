<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use Illuminate\Support\Str;

enum OpencastWorkflowState: string
{
    use InvokableCases;

    case INSTANTIATED = "EVENTS.EVENTS.STATUS.PENDING";
    case RUNNING = "EVENTS.EVENTS.STATUS.PROCESSING";
    case STOPPED = "EVENTS.EVENTS.STATUS.PROCESSING_CANCELLED";
    case PAUSED = "EVENTS.EVENTS.STATUS.PAUSED";
    case SUCCEEDED = "EVENTS.EVENTS.STATUS.PROCESSED";
    case FAILED = "EVENTS.EVENTS.STATUS.PROCESSING_FAILURE";

    //lowercase state's name
    public function lower(): string
    {
        return Str::lower($this->name);
    }
}
