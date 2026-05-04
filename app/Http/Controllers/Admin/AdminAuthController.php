<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function create(Request $request): RedirectResponse|View
    {
        if ($request->user()?->is_admin) {
            return redirect()->route('admin.customers.index');
        }

        return view('admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        $request->session()->regenerate();

        if (! Auth::user()->is_admin) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        return redirect()->intended(route('admin.customers.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
