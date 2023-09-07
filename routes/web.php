<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Models\Project;
use App\Models\Section;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $projects = Project::with('authors')->get();
    return Inertia::render('Dashboard')
        ->with([
            'projects' => $projects,
        ]);
})->middleware(['auth', 'verified'])->name('dashboard');


//users routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');

//projects routes
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

require __DIR__.'/auth.php';
