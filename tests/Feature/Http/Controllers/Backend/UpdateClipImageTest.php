<?php

use App\Enums\Role;
use App\Models\Clip;

it('can update clip image', function () {
    signInRole(Role::MODERATOR);
    $clip = Clip::factory()->create();

    $this->put(route('update.clip.image', $clip), ['imageID' => 1])
        ->assertRedirectToRoute('clips.edit', $clip);

    $clip->refresh();
    expect($clip->image_id)->toEqual(1);
});
