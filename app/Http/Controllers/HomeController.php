<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Single-page marketing site (Blade sections prepared for future DB-driven content).
     */
    public function __invoke(Request $request)
    {
        return view('pages.home');
    }
}
