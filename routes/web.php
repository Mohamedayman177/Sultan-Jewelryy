<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::post('/customers', [CustomerController::class, 'store'])
    ->name('customers.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('login', [AdminAuthController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.customers.index'))->name('dashboard');
        Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Future routes (forms, admin, API-backed pages)
|--------------------------------------------------------------------------
|
| Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
| Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
|
*/
