<?php

use App\Enums\Role;
use App\Models\Clip;
use App\Models\Document;
use App\Models\Series;
use Facades\Tests\Setup\ClipFactory;
use Facades\Tests\Setup\SeriesFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses()->group('backend');

it('shows an upload document box in series edit page', function () {
    signInRole(Role::ADMIN);
    get(route('series.edit', Series::factory()->create()))
        ->assertSee(__('common.forms.Upload a document'));
});

it('shows an upload document box in clip edit page', function () {
    signInRole(Role::ADMIN);
    get(route('clips.edit', Clip::factory()->create()))
        ->assertSee(__('common.forms.Upload a document'));
});

it('requires a file for uploading a document', function () {
    signInRole(Role::ADMIN);
    $attributes = [
        'document' => '',
        'type' => 'series',
        'id' => 'id',
    ];

    post(route('documents.upload'), $attributes)->assertSessionHasErrors('document');

    $attributes = [
        'document' => 'test.txt',
        'type' => 'series',
        'id' => 'id',
    ];
    post(route('documents.upload'), $attributes)->assertSessionHasErrors('document');
});

it('requires a series or clip type for uploading a document', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    signInRole(Role::ADMIN);
    $attributes = [
        'document' => $file,
        'id' => 'id',
    ];
    post(route('documents.upload'), $attributes)->assertSessionHasErrors('type');

    $attributes = [
        'document' => $file,
        'type' => 'document',
        'id' => 'id',
    ];
    post(route('documents.upload'), $attributes)->assertSessionHasErrors('type');

    $attributes = [
        'document' => $file,
        'type' => 'series',
        'id' => 'id',
    ];
    post(route('documents.upload'), $attributes)->assertSessionDoesntHaveErrors('type');

    $attributes = [
        'document' => $file,
        'type' => 'clip',
        'id' => 'id',
    ];
    post(route('documents.upload'), $attributes)->assertSessionDoesntHaveErrors('type');
});

it('requires an existing resource for the given type', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    signInRole(Role::ADMIN);

    $attributes = [
        'document' => $file,
        'type' => 'series',
        'id' => '100',
    ];

    post(route('documents.upload'), $attributes)->assertNotFound();
});

it('denies access to uploading documents for members that they do not belong to the series', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $series = SeriesFactory::create();
    signInRole(Role::MODERATOR);

    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'series',
        'id' => $series->id,
    ])->assertForbidden();
});

it('allows uploading documents to series owner', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();

    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'series',
        'id' => $series->id,
    ])->assertSessionDoesntHaveErrors('document');

    Storage::disk('documents')->assertExists($series->documents()->first()->save_path);

    assertDatabaseHas('documents', ['name' => 'document.pdf']);
    expect($series->documents()->count())->toBe(1);
});

it('lists all documents with options in series edit page', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'series',
        'id' => $series->id,
    ]);

    get(route('series.edit', $series))
        ->assertSee('document.pdf')
        ->assertSee('view-document')
        ->assertSee('delete-document');
});

it('denies documents uploads to non clip members', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $clip = ClipFactory::create();
    signInRole(Role::MODERATOR);

    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'clip',
        'id' => $clip->id,
    ])->assertForbidden();
});

it('allows documents uploads to series for clip owners', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'clip',
        'id' => $clip->id,
    ]);

    Storage::disk('documents')->assertExists($clip->documents()->first()->save_path);

    assertDatabaseHas('documents', ['name' => 'document.pdf']);
    expect($clip->documents()->count())->toBe(1);
});

it('lists all documents in clip edit page', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'clip',
        'id' => $clip->id,
    ]);

    get(route('clips.edit', $clip))->assertSee('document.pdf');
});

it('allows to series owner to view uploaded documents', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('testingDocument.pdf', '100', 'application/pdf');
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'series',
        'id' => $series->id,
    ]);
    $document = $series->documents()->first();

    /*
     *  In testing a FileNotFound exception will be thrown therefore
     *  assert a redirect instead of 200.
     */
    get(route('document.series.view', [$series, $document]))->assertRedirect();
});

it('allows to clip owners to view uploaded documents', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('testingDocument.pdf', '100', 'application/pdf');
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'clip',
        'id' => $clip->id,
    ]);
    $document = $clip->documents()->first();

    /*
     *  In testing a FileNotFound exception will be thrown therefore
     *  assert a redirect instead of 200.
     */
    get(route('document.clip.view', [$clip, $document]))->assertRedirect();
});

it('denies viewing documents to a non series members', function () {
    $series = SeriesFactory::create();
    $document = Document::factory()->create();
    $series->addDocument($document);
    signInRole(Role::MODERATOR);

    get(route('document.series.view', [$series, $document]))->assertForbidden();
});

it('denies viewing documents to a non clip member', function () {
    $clip = ClipFactory::create();
    $document = Document::factory()->create();
    $clip->addDocument($document);
    signInRole(Role::MODERATOR);

    get(route('document.clip.view', [$clip, $document]))->assertForbidden();
});

it('allows series members to delete a document', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'series',
        'id' => $series->id,
    ]);
    $document = $series->documents()->first();

    delete(route('documents.destroy', $document))->assertRedirect();
    assertDatabaseMissing('documents', ['id' => $document->id]);
    Storage::disk('documents')->assertMissing($document->save_path);
});

it('denies deleting a document to non series members', function () {
    $series = SeriesFactory::create();
    $document = Document::factory()->create();
    $series->addDocument($document);
    signInRole(Role::MODERATOR);

    delete(route('documents.destroy', $document))->assertForbidden();
});

test('deleting a series will delete also related documents', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $series = SeriesFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'series',
        'id' => $series->id,
    ]);
    $document = $series->documents()->first();

    Storage::disk('documents')->assertExists($document->save_path);

    $series->delete();

    assertDatabaseMissing('series', ['id' => $series->id]);
    assertDatabaseMissing('documentables', [
        'documentable_id' => $series->id,
        'documentable_type' => 'series',
    ]);
    assertDatabaseMissing('documents', ['name' => $document->name]);
    Storage::disk('documents')->assertMissing($document->save_path);
});

test('deleting a clip will delete also related documents', function () {
    Storage::fake('documents');
    $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');
    $clip = ClipFactory::ownedBy(signInRole(Role::MODERATOR))->create();
    post(route('documents.upload'), [
        'document' => $file,
        'type' => 'clip',
        'id' => $clip->id,
    ]);
    $document = $clip->documents()->first();

    Storage::disk('documents')->assertExists($document->save_path);

    $clip->delete();

    assertDatabaseMissing('clips', ['id' => $clip->id]);
    assertDatabaseMissing('documentables', [
        'documentable_id' => $clip->id,
        'documentable_type' => 'clip',
    ]);
    assertDatabaseMissing('documents', ['name' => $document->name]);
});
