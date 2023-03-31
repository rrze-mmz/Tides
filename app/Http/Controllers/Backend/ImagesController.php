<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Rules\ValidImageFile;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\ImageManagerStatic;
use Mhor\MediaInfo\Exception\UnknownTrackTypeException;
use Mhor\MediaInfo\MediaInfo;

class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('backend.images.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     */
    public function create()
    {
        //Only portal admins can create new images.
        $this->authorize('administrate-admin-portal-pages');

        return view('backend.images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Only portal admins can persist new images to DB
        $this->authorize('administrate-admin-portal-pages');
        $validated = $request->validate([
            'description' => ['required', 'string'],
            'image' => ['required', 'string', new ValidImageFile(['image/png', 'image/jpeg'])],
        ]);

        $uploadedImage = new File(Storage::path($validated['image']));

        $fileName = Storage::disk('images')->putFile(
            path: '',
            file: $uploadedImage,
        );

        ImageManagerStatic::make(Storage::disk('images')
            ->get($fileName))
            ->resize(300, 200)
            ->save('images/Thumbnails/'.$fileName);

        $image = Image::create([
            'file_name' => $fileName,
            'description' => $validated['description'],
            'file_path' => 'images/'.$fileName,
            'thumbnail_path' => 'images/Thumbnails/'.$fileName,
            'file_size' => Storage::disk('images')->size($fileName),
            'mime_type' => Storage::disk('images')->mimeType($fileName),
        ]);

        return to_route('images.edit', $image);
    }

    /**
     * Display the specified resource.
     *
     * @throws UnknownTrackTypeException
     */
    public function show(Image $image, MediaInfo $mediaInfo)
    {
        $mediaInfoContainer = $mediaInfo->getInfo(Storage::disk('images')->path($image->file_name));

        return view('backend.images.show', compact('image', 'mediaInfoContainer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image, MediaInfo $mediaInfo)
    {
        //Only portal admins can create new images.
        $this->authorize('administrate-admin-portal-pages');
        $mediaInfoContainer = $mediaInfo->getInfo(Storage::disk('images')->path($image->file_name));

        return view('backend.images.edit', compact('image', 'mediaInfoContainer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image, MediaInfo $mediaInfo)
    {
        //Only portal admins can create new images.
        $this->authorize('administrate-admin-portal-pages');

        $validated = $request->validate([
            'description' => ['required', 'string'],
        ]);

        $image->description = $validated['description'];
        $image->save();
        $mediaInfoContainer = $mediaInfo->getInfo(Storage::disk('images')->path($image->file_name));

        session()->flash('flashMessage', 'Image updated successfully');

        return to_route('images.edit', $image)->with([$mediaInfoContainer]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //Only portal admins can delete images.
        $this->authorize('administrate-admin-portal-pages');
    }
}
