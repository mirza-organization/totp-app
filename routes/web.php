<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TotpController;
use App\Http\Controllers\TwoFactorAuthController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('MyFirstWelcomePage');
// })->name('home');

Route::get('/', [HomeController::class, 'show'])->name('home');

Route::controller(TotpController::class)->group(function () {
    Route::get('/search-account', 'search')->name('search.account');
    Route::put('/edit-account', 'edit')->name('edit.account');
    Route::delete('/delete-account', 'delete')->name('delete.account');
    Route::post('/add-account', 'store')->name('add.account');
});

Route::get('/2FA/{uuid}', [TwoFactorAuthController::class, 'show'])->name('show.2FA');
