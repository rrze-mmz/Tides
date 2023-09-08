<?php

use App\Enums\Role;
use App\Http\Livewire\DeleteModalWindow;
use App\Http\Livewire\ImagesDataTable;
use App\Models\Image;
use Illuminate\Foundation\Testing\WithFaker;

use function Pest\Laravel\get;

uses(WithFaker::class);
uses()->group('backend');

beforeEach(function () {
    signInRole(Role::ADMIN);
});

it('loads a images livewire component on images index page', function () {
    get(route('images.index'))->assertSeeLivewire('images-data-table');
});

it('can search for images description on images index page', function () {
    [$firstImage, $secondImage] = Image::factory(2)->create();

    Livewire::test(ImagesDataTable::class)
        ->assertSee($firstImage->description)
        ->assertSee($secondImage->description)
        ->set('search', $firstImage->description)
        ->assertSee($firstImage->description)
        ->assertDontSee($secondImage->description);
});

it('can sorts an image by his file name', function () {
    $betaImage = Image::factory()->create(['file_name' => 'beta_file_name.png']);
    $alphaImage = Image::factory()->create(['file_name' => 'alpha_file_name']);

    Livewire::test(ImagesDataTable::class)
        ->call('sortBy', 'file_name')
        ->assertSeeInOrder([$alphaImage->file_name, $betaImage->file_name]);
});

it('has a delete livewire component for deleting images', function () {
    Image::factory(2)->create();

    Livewire::actingAs(auth()->user());

    get(route('images.index'))->assertSeeLivewire(DeleteModalWindow::class);
});
