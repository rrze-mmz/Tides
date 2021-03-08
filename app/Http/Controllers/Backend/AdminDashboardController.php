<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Clip;

class AdminDashboardController extends Controller
{

    /**
     * Dashboard for the logged in user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('dashboard.index', [
            'clips' => auth()->user()->clips()->orderByDesc('updated_at')->limit(10)->get(),
        ]);
    }
}
