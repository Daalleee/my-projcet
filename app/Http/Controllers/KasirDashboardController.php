<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirDashboardController extends Controller
{
    public function index()
    {
        return view('kasir.dashboard'); // nanti buat view ini
    }
}
