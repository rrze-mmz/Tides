<?php

namespace App\Http\Controllers\Backend\Traits;

use App\Models\Image;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

trait HandlesFilePondFiles
{
    public function uploadAndCreateImage(string $filePath, string $description): Image
    {
        $uploadedImage = new File(Storage::path($filePath));

        $fileName = Storage::disk('images')->putFile(
            path: '',
            file: $uploadedImage,
        );

        ImageManagerStatic::make(Storage::disk('images')
            ->get($fileName))
            ->resize(300, 200)
            ->save('images/Thumbnails/'.$fileName);

        return Image::create([
            'file_name' => $fileName,
            'description' => $description,
            'file_path' => 'images/'.$fileName,
            'thumbnail_path' => 'images/Thumbnails/'.$fileName,
            'file_size' => Storage::disk('images')->size($fileName),
            'mime_type' => Storage::disk('images')->mimeType($fileName),
        ]);
    }
}
