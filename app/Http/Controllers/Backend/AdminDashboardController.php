<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'clips' => Clip::orderByDesc('updated_at')->limit(10)->get(),
        ]);
    }
}
