<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Single-page marketing site (Blade sections prepared for future DB-driven content).
     */
    public function __invoke(Request $request)
    {
        $waDigits = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));

        return view('pages.home', [
            'services' => Service::query()->active()->ordered()->get(),
            'whatsappBaseUrl' => $waDigits !== '' ? 'https://wa.me/'.$waDigits : '#',
        ]);
    }
}
