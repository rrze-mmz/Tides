<?php

it('does not use debugging functions')->expect(['dd', 'dump', 'ray', 'Debugbar'])->not->toBeUsed();

test('app')
    ->expect('App\Enums')
    ->toBeEnums();

test('app base models')
    ->expect('App\Models')
    ->toExtend('App\Models\BaseModel')
    ->ignoring('App\Models\Notification')
    ->ignoring('App\Models\User')
    ->ignoring('App\Models\Traits');
