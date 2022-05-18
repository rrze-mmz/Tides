<?php

namespace App\Models\Traits;

use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use PhpParser\Comment\Doc;

trait Documentable
{
    /*
     * Given model documents relationship
     */
    public function documents(): MorphToMany
    {
        return $this->morphToMany(Document::class, 'documentable')->withTimestamps();
    }

    /**
     * Assigns a document to a give type
     *
     * @param Document $document
     * @return void
     */
    public function addDocument(Document $document): void
    {
        $this->documents()->attach($document);
    }
}
