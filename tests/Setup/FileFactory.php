<?php

namespace Tests\Setup;

use Illuminate\Http\UploadedFile;

class FileFactory
{
    public function videoFile(): UploadedFile
    {
        return
            new UploadedFile(
                storage_path().'/tests/Big_Buck_Bunny.mp4',
                'Big_Buck_Bunny.mp4',
                'video/mp4',
                null,
                true
            );
    }

    public function audioFile(): UploadedFile
    {
        return
            new UploadedFile(
                storage_path().'/tests/Sample_Audio_file.mp3',
                'Sample_Audio_file.mp3',
                'audio/mpeg',
                null,
                true
            );
    }

    public function imageFile(): UploadedFile
    {
        return
            new UploadedFile(
                storage_path().'/tests/creative-commons.png',
                'creative-commons.png',
                'image/x-png',
                null,
                true
            );
    }

    public function simpleFile(): UploadedFile
    {
        return
            UploadedFile::fake()->create('.DS_Store', '10', 'application/octet-stream');
    }
}
