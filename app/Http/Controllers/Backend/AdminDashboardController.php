<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
