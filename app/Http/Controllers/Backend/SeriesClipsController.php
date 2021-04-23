<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Series;

class SeriesClipsController extends Controller
{

    public function create(Series $series)
    {
        $this->authorize('edit', $series);

        return 'create function';
    }
}
