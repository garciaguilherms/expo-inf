<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ProviderController;
use App\Services\GoogleSheetService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/{provider}/redirect', [ProviderController::class, 'redirect'])
    ->name('auth.redirect');

Route::get('/auth/{provider}/callback', [ProviderController::class, 'callback'])
    ->name('auth.callback');

Route::get('/homepage', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/users', [UserController::class, 'index'])
    ->name('users.index');

// Projects
Route::get('/', function (GoogleSheetService $googleSheetService) {
    $projects = $googleSheetService->getProjects();
    return Inertia::render('Dashboard', [
        'projects' => $projects,
    ]);
})->name('dashboard');

Route::get('/projects/search', [ProjectController::class, 'search'])->name('projects.search');

Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/projects/create', [ProjectController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('projects.create');

Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/projects/ranking', [ProjectController::class, 'ranking'])->name('projects.ranking');

Route::post('/projects', [ProjectController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('projects.store');

Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('projects.edit');

Route::put('/projects/{project}', [ProjectController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('projects.update');

Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('projects.destroy');


//Sections
Route::get('/galleries', [SectionController::class, 'index'])->name('sections.index');

Route::get('/all-sections', [SectionController::class, 'allSections'])->name('sections.allSections');

Route::get('/galleries/search', [SectionController::class, 'search'])->name('sections.search');

Route::get('/galleries/create', [SectionController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('sections.create');

Route::post('/galleries', [SectionController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('sections.store');

Route::get('/galleries/{galery}', [SectionController::class, 'show'])
    ->name('sections.show');

Route::get('/galleries/{galery}/edit', [SectionController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('sections.edit');

Route::put('/galleries/{galery}', [SectionController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('sections.update');

Route::delete('/galleries/{galery}', [SectionController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('sections.destroy');

Route::post('/projects/{project}/comments', [CommentController::class, 'store'])
    ->name('comments.store');

Route::post('/projects/{project}/invite', [ProjectController::class, 'createInvite'])
    ->middleware(['auth', 'verified'])
    ->name('projects.createInvite');

Route::get('/invite/{token}', [ProjectController::class, 'acceptInvite'])
    ->middleware(['auth', 'verified'])
    ->name('projects.acceptInvite');

Route::post('/projects/{project}/rating', [RatingController::class, 'store'])
    ->name('rating.store');

Route::get('/projects/{project}/rating', [RatingController::class, 'userRating'])
    ->name('rating.user-rating');

require __DIR__.'/auth.php';
