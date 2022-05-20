<?php

namespace Tests\Feature\Backend;

use App\Models\Clip;
use App\Models\Document;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Facades\Tests\Setup\SeriesFactory;
use Facades\Tests\Setup\ClipFactory;
use Tests\TestCase;

class ManageDocumentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_an_upload_document_box_in_series_edit_page(): void
    {
        $this->signInRole('admin');

        $this->get(route('series.edit', Series::factory()->create()))
            ->assertSee('Upload a document');
    }

    /** @test */
    public function it_shows_an_upload_document_box_in_clip_edit_page(): void
    {
        $this->signInRole('admin');

        $this->get(route('clips.edit', Clip::factory()->create()))
            ->assertSee('Upload a document');
    }

    /** @test */
    public function it_requires_a_file_for_uploading_a_document(): void
    {
        $this->signInRole('admin');

        $attributes = [
            'document' => '',
            'type'     => 'series',
            'id'       => 'id',
        ];
        $this->post(route('documents.upload'), $attributes)->assertSessionHasErrors('document');

        $attributes = [
            'document' => 'test.txt',
            'type'     => 'series',
            'id'       => 'id',
        ];
        $this->post(route('documents.upload'), $attributes)->assertSessionHasErrors('document');
    }

    /** @test */
    public function it_requires_a_series_or_clip_type_for_uploading_a_document(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $this->signInRole('admin');

        $attributes = [
            'document' => $file,
            'id'       => 'id',
        ];
        $this->post(route('documents.upload'), $attributes)->assertSessionHasErrors('type');

        $attributes = [
            'document' => $file,
            'type'     => 'document',
            'id'       => 'id',
        ];
        $this->post(route('documents.upload'), $attributes)->assertSessionHasErrors('type');

        $attributes = [
            'document' => $file,
            'type'     => 'series',
            'id'       => 'id',
        ];
        $this->post(route('documents.upload'), $attributes)->assertSessionDoesntHaveErrors('type');

        $attributes = [
            'document' => $file,
            'type'     => 'clip',
            'id'       => 'id',
        ];
        $this->post(route('documents.upload'), $attributes)->assertSessionDoesntHaveErrors('type');
    }

    /** @test */
    public function it_requires_an_existing_resource_for_the_given_type(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $this->signInRole('admin');

        $attributes = [
            'document' => $file,
            'type'     => 'series',
            'id'       => '100',
        ];

        $this->post(route('documents.upload'), $attributes)->assertNotFound();
    }

    /** @test */
    public function a_not_series_member_cannot_upload_documents(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $series = SeriesFactory::create();

        $this->signInRole('moderator');

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'series',
            'id'       => $series->id
        ])->assertForbidden();
    }

    /** @test */
    public function a_series_owner_can_upload_a_document_to_series(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'series',
            'id'       => $series->id
        ])->assertSessionDoesntHaveErrors('document');

        Storage::disk('documents')->assertExists($series->documents()->first()->save_path);

        $this->assertDatabaseHas('documents', ['name' => 'document.pdf']);
        $this->assertEquals(1, $series->documents()->count());
    }

    /** @test */
    public function it_lists_all_documents_with_options_in_series_edit_page(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'series',
            'id'       => $series->id
        ]);

        $this->get(route('series.edit', $series))
            ->assertSee('document.pdf')
            ->assertSee('view-document')
            ->assertSee('delete-document');
    }

    /** @test */
    public function a_not_clip_member_cannot_upload_documents(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $clip = ClipFactory::create();

        $this->signInRole('moderator');

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'clip',
            'id'       => $clip->id
        ])->assertForbidden();
    }

    /** @test */
    public function a_clip_owner_can_upload_a_document_to_series(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $clip = ClipFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'clip',
            'id'       => $clip->id
        ]);

        Storage::disk('documents')->assertExists($clip->documents()->first()->save_path);

        $this->assertDatabaseHas('documents', ['name' => 'document.pdf']);
        $this->assertEquals(1, $clip->documents()->count());
    }

    /** @test */
    public function it_lists_all_documents_in_clip_edit_page(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $clip = ClipFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'clip',
            'id'       => $clip->id
        ]);

        $this->get(route('clips.edit', $clip))->assertSee('document.pdf');
    }

    /** @test */
    public function a_series_owner_can_view_an_uploaded_document(): void
    {
        $file = UploadedFile::fake()->create('testingDocument.pdf', '100', 'application/pdf');

        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'series',
            'id'       => $series->id
        ]);

        $document = $series->documents()->first();

        $this->get(route('document.series.view', [$series, $document]))->assertOk();
    }

    /** @test */
    public function a_clip_owner_can_view_an_uploaded_document(): void
    {
        $file = UploadedFile::fake()->create('testingDocument.pdf', '100', 'application/pdf');

        $clip = ClipFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'clip',
            'id'       => $clip->id
        ]);

        $document = $clip->documents()->first();

        $this->get(route('document.clip.view', [$clip, $document]))->assertOk();
    }

    /** @test */
    public function a_not_series_member_is_not_allowed_to_view_series_documents(): void
    {
        $series = SeriesFactory::create();
        $document = Document::factory()->create();

        $series->addDocument($document);

        $this->signInRole('moderator');

        $this->get(route('document.series.view', [$series, $document]))->assertForbidden();
    }

    /** @test */
    public function a_not_clip_member_is_not_allowed_to_view_clip_documents(): void
    {
        $clip = ClipFactory::create();
        $document = Document::factory()->create();

        $clip->addDocument($document);

        $this->signInRole('moderator');

        $this->get(route('document.clip.view', [$clip, $document]))->assertForbidden();
    }

    /** @test */
    public function a_series_member_can_delete_a_document(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'series',
            'id'       => $series->id
        ]);

        $document = $series->documents()->first();

        $this->delete(route('documents.destroy', $document))->assertRedirect();

        $this->assertDatabaseMissing('documents', ['id' => $document->id]);

        Storage::disk('documents')->assertMissing($document->save_path);
    }

    /** @test */
    public function a_not_series_member_is_not_allowed_to_delete_a_series_document(): void
    {
        $series = SeriesFactory::create();
        $document = Document::factory()->create();

        $series->addDocument($document);

        $this->signInRole('moderator');

        $this->delete(route('documents.destroy', $document))->assertForbidden();
    }

    /** @test */
    public function deleting_a_series_will_delete_also_related_documents(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'series',
            'id'       => $series->id
        ]);

        $document = $series->documents()->first();
        Storage::disk('documents')->assertExists($document->save_path);

        $series->delete();

        $this->assertDatabaseMissing('series', ['id' => $series->id]);
        $this->assertDatabaseMissing('documentables', [
            'documentable_id'   => $series->id,
            'documentable_type' => 'series',
        ]);
        $this->assertDatabaseMissing('documents', ['name' => $document->name]);

        Storage::disk('documents')->assertMissing($document->save_path);
    }

    /** @test */
    public function deleting_a_clip_will_delete_also_related_documents(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $clip = ClipFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'clip',
            'id'       => $clip->id
        ]);

        $document = $clip->documents()->first();
        Storage::disk('documents')->assertExists($document->save_path);


        $clip->delete();
        
        $this->assertDatabaseMissing('clips', ['id' => $clip->id]);
        $this->assertDatabaseMissing('documentables', [
            'documentable_id'   => $clip->id,
            'documentable_type' => 'clip',
        ]);
        $this->assertDatabaseMissing('documents', ['name' => $document->name]);
    }
}
