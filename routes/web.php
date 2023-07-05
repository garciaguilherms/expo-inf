<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReviewController;
use App\Models\Album;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $current_user_id = auth()->id();
    $albums = Album::select('albums.*', 'ratings.rating')
        ->leftJoin('ratings', function ($join) use ($current_user_id) {
            $join->on('albums.id', '=', 'ratings.album_id')
                ->where('ratings.user_id', '=', $current_user_id);
        })
        ->orderBy('title', 'asc')
        ->get();
    return Inertia::render('Dashboard')
        ->with('albums', $albums);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/albums', [AlbumController::class, 'index'])
    ->name('albums.index');

Route::post('/albums', [AlbumController::class, 'store'])
    ->middleware('auth')
    ->name('albums.store');

Route::get('/albums/add', [AlbumController::class, 'create'])
    ->middleware('auth')
    ->name('albums.create');

Route::get('/reviews', [ReviewController::class, 'listAll'])
    ->name('reviews.listAll');

Route::post('/albums/{album}/ratings', [RatingController::class, 'store'])
    ->middleware('auth')
    ->name('ratings.store');

Route::post('/albums/{album}/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');

Route::get('/albums/{album}/review', [ReviewController::class, 'index'])
    ->middleware('auth')
    ->name('review');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
