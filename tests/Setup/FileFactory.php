<?php

namespace Tests\Setup;

use Illuminate\Http\UploadedFile;

class FileFactory {

    public function videoFile()
    {
        return new UploadedFile(storage_path() . '/tests/Big_Buck_Bunny.mp4', 'Big_Buck_Bunny.mp4', 'video/mp4', null, true);
    }
}
