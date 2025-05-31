<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $motors = Motor::where('status', 'available')->paginate(9);
        return view('home', compact('motors'));
    }
}
