<?php

use App\Enums\Role;
use App\Models\Livestream;

use function Pest\Laravel\post;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('denies post requests to simple users', function () {
    post(route('reserveLivestreamRoom', ['location' => 'test-lecture-hall']))->assertRedirectToRoute('login');
});

it('denies post requests to students or moderators', function () {
    $this->signInRole(Role::STUDENT);
    post(route('reserveLivestreamRoom', ['location' => 'test-lecture-hall']))->assertForbidden();
    auth()->logout();

    $this->signInRole(Role::MODERATOR);
    post(route('reserveLivestreamRoom', ['location' => 'test-lecture-hall']))->assertForbidden();
});

it('validates the request to reserve a livestream room', function () {
    $this->signInRole(Role::ASSISTANT);

    post(route('reserveLivestreamRoom', ['location' => '']))->assertSessionHasErrors('location');
});

it('reserves a livestream room for a clip', function () {
    $this->signInRole(Role::ASSISTANT);
    Livestream::factory()->create(['name' => 'test-location']);
    post(route('reserveLivestreamRoom', ['location' => 'test-location']));
});
