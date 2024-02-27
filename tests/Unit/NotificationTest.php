<?php

use App\Models\Notification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('has an users method for model', function () {
    expect(Notification::factory()->create()->users())->toBeInstanceOf(BelongsTo::class);
});
