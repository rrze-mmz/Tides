<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'id'       => ['required', 'integer'],
            'type'     => ['required', Rule::in(['series', 'clip'])], //types are not dynamic
            'document' => ['required', 'file', 'mimes:pdf,xsl,doc']
        ]);

        $model = match ($validated['type']) {
            'series' => 'App\Models\Series',
            'clip' => 'App\Models\Clip',
        };
        $resource = $model::findOrFail($validated['id']);

        $document = Document::create([
            'name'      => $name = $validated['document']->getClientOriginalName(),
            'save_path' => $validated['document']
                ->storeAs($validated['type'] . '_' . $resource->id, $name, 'documents')
        ]);

        $resource->addDocument($document);

        return back()->with('File uploaded successfully', 'File uploaded successfully');
    }
}
