<?php

it('does not use debugging functions')->expect(['dd', 'dump', 'ray', 'Debugbar'])->not->toBeUsed();

it('expects Enums directory to contain enums')
    ->expect('App\Enums')
    ->toBeEnums();

it('expect some models to extend Base model')
    ->expect('App\Models')
    ->toExtend('App\Models\BaseModel')
    ->ignoring('App\Models\Notification')
    ->ignoring('App\Models\User')
    ->ignoring('App\Models\Traits')
    ->ignoring('App\Models\StatsModel')
    ->ignoring('App\Models\StatsCounter')
    ->ignoring('App\Models\StatsLog');
