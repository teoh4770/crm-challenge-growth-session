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

Route::get('dashboard', [ClientController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('clients', ClientController::class)->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
