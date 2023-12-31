<?php

namespace App\Models\Traits;

use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
     */
    public function addDocument(Document $document): void
    {
        $this->documents()->attach($document);
    }
}
