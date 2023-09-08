<?php

use App\Enums\Role;
use App\Http\Livewire\DeleteModalWindow;
use App\Models\Image;
use Facades\Tests\Setup\FileFactory;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;

uses()->group('backend');

beforeEach(function () {
    $disk = Storage::fake('images');
    $disk->putFileAs('', FileFactory::imageFile(), 'avatar.png');

    $this->image = Image::find(1);
    signInRole(Role::ADMIN);
});

it('loads a delete modal livewire component in images index', function () {
    get(route('images.index'))->assertSeeLivewire(DeleteModalWindow::class);
});

it('can delete an image from the livewire modal window', function () {
    Livewire::test(DeleteModalWindow::class, [
        'model' => $this->image,
    ])->call(('delete'));

    assertDatabaseMissing('images', ['id' => $this->image->id]);

    get(route('images.index'))->assertDontSeeLivewire(DeleteModalWindow::class);
});

it('deletes image and thumbnail files from disk', function () {
    Livewire::test(DeleteModalWindow::class, [
        'model' => $this->image,
    ])->call(('delete'));

    Storage::disk('images')->assertMissing($this->image->file_path);
    Storage::disk('images')->assertMissing('Thumbnails/'.$this->image->file_path);
});
