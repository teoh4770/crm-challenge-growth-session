<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectUploadController;
use App\Http\Controllers\TaskUploadController;
use App\Http\Controllers\TaskUploadDeleteController;
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

Route::post('upload/projects/{project}', ProjectUploadController::class)->name('upload.project')->middleware(['auth', 'verified']);
Route::post('upload/tasks/{task}', TaskUploadController::class)->name('upload.task')->middleware(['auth', 'verified']);

Route::delete('upload/tasks/{task}/{media}', TaskUploadDeleteController::class)->name('tasks.upload.destroy')->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
