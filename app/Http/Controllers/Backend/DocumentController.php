<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;
use App\Models\Document;
use App\Models\Series;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function upload(Request $request): RedirectResponse
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

        $this->authorize('edit-' . str($validated['type'])->plural(), $resource);

        $document = Document::create([
            'name'      => $name = $validated['document']->getClientOriginalName(),
            'save_path' => $validated['document']
                ->store($validated['type'] . '_' . $resource->id, 'documents')
        ]);

        $resource->addDocument($document);

        return back()->with('flashMessage', 'File uploaded successfully');
    }

    /**
     * @param Series $series
     * @param Document $document
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function viewSeriesDocument(Series $series, Document $document): BinaryFileResponse
    {
        $this->authorize('edit-series', $series);

        return response()->file(public_path('documents/') . $document->save_path);
    }

    /**
     * @param Clip $clip
     * @param Document $document
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function viewClipDocument(Clip $clip, Document $document): BinaryFileResponse
    {
        $this->authorize('edit-clips', $clip);

        return response()->file(public_path('documents/') . $document->save_path);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Document $document): RedirectResponse
    {
        if ($document->series->isNotEmpty()) {
            $this->authorize('delete-series', $document->series()->first());
        } else {
            $this->authorize('edit-clips', $document->clips()->first());
        }

        $document->delete();

        return back()->with('flashMessage', 'File deleted successfully');
    }
}
