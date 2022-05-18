<?php

namespace Tests\Feature\Backend;

use App\Models\Clip;
use App\Models\Series;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Facades\Tests\Setup\SeriesFactory;
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

        Storage::disk('documents')->assertExists('/series_1/' . $file->getClientOriginalName());

        $this->assertDatabaseHas('documents', ['name' => 'document.pdf']);
        $this->assertEquals(1, $series->documents()->count());
    }

    /** @test */
    public function it_lists_all_documents_in_series_edit_page(): void
    {
        Storage::fake('documents');

        $file = UploadedFile::fake()->create('document.pdf', '100', 'application/pdf');

        $series = SeriesFactory::ownedBy($this->signInRole('moderator'))->create();

        $this->post(route('documents.upload'), [
            'document' => $file,
            'type'     => 'series',
            'id'       => $series->id
        ])->assertSessionDoesntHaveErrors('document');

        $this->get(route('series.edit', $series))->assertSee('document.pdf');
    }
}
