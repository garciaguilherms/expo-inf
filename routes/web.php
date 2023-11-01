<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ProviderController;
use App\Models\Project;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [ProviderController::class, 'callback']);

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

//search routes
Route::get('/projects/search/{term}', [ProjectController::class, 'search'])->name('search');

//users routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');

//projects routes
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/ranking', [ProjectController::class, 'ranking'])->name('projects.ranking');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');


//sections routes
Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
Route::get('/all-sections', [SectionController::class, 'allSections'])->name('sections.allSections');
Route::get('/sections/create', [SectionController::class, 'create'])->name('sections.create');
Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

//comments routes
Route::post('/projects/{project}/comments', [CommentController::class, 'store'])->name('comments.store');

//rating routes
Route::post('/projects/{project}/rating', [RatingController::class, 'store'])->name('rating.store');
Route::get('/projects/{project}/rating', [RatingController::class, 'userRating'])->name('rating.user-rating');

require __DIR__.'/auth.php';
