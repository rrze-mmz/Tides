<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $uploadedFile = UploadedFile::fake()->create('image.png', '300', 'image/png');

        return [
            'description' => 'just a test image',
            'file_name' => $uploadedFile->getClientOriginalName(),
            'file_path' => 'images',
            'thumbnail_path' => $uploadedFile->getClientOriginalName().'_thumb.png',
            'mime_type' => 'image/png',
            'file_size' => '300',
        ];
    }
}
