<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectUploadController;
use App\Http\Controllers\TaskUploadController;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// based on user type, we render different dashboard content?
Route::get('dashboard', function() {
    $clientAmount = Client::query()->count();
    $projectAmount = Project::query()->count();

    return Inertia::render('Dashboard', [
        'clientAmount' => $clientAmount,
        'projectAmount' => $projectAmount,
    ]);
})->name('dashboard')->middleware(['auth', 'verified']);

Route::resource('clients', ClientController::class)
    ->except(['show'])
    ->middleware(['auth', 'verified']);

Route::resource('projects', ProjectController::class)
    ->middleware(['auth', 'verified']);

Route::resource('tasks', TaskController::class)
    ->middleware(['auth', 'verified']);

Route::prefix('upload')->middleware(['auth', 'verified'])->group(function () {
    Route::post('projects/{project}', [ProjectUploadController::class, 'store'])->name('projects.upload.store');
    Route::delete('projects/{media}', [ProjectUploadController::class, 'destroy'])->name('projects.upload.destroy');

    Route::post('tasks/{task}', [TaskUploadController::class, 'store'])->name('tasks.upload.store');
    Route::delete('tasks/{media}', [TaskUploadController::class, 'destroy'])->name('tasks.upload.destroy');
});

require __DIR__.'/settings.php';
