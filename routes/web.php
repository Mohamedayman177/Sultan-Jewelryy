<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::post('/customers', [CustomerController::class, 'store'])
    ->name('customers.store');

/*
|--------------------------------------------------------------------------
| Future routes (forms, admin, API-backed pages)
|--------------------------------------------------------------------------
|
| Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
| Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
|
*/
