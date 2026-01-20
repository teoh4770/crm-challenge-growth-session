<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');


Route::resource('clients', ClientController::class)
    ->middleware(['auth', 'verified', 'can:manage clients']);

Route::get('dashboard', function() {
    return 'dashboard';
})->name('dashboard');

require __DIR__.'/settings.php';
