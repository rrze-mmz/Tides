<?php

uses()->group('unit');

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
