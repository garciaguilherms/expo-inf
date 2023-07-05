<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Rating;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index(Album $album): \Inertia\Response
    {
        return Inertia::render('Review', [
            'album' => $album,
        ]);
    }
    public function store(Request $request, Album $album): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'review' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $data = [
            'user_id' => $request->user()->id,
            'album_id' => $album->id,
            'review' => $request->input('review'),
        ];

        if ($request->has('rating')) {
            $data['rating'] = $request->input('rating');
        }

        Rating::updateOrCreate(
            ['user_id' => $data['user_id'], 'album_id' => $data['album_id']],
            $data
        );

        return redirect()->route('dashboard');
    }

    public function listAll(): \Inertia\Response
    {
        $reviews = Rating::select('ratings.*', 'albums.title', 'albums.cover_image', 'users.name')
            ->leftJoin('albums', 'ratings.album_id', '=', 'albums.id')
            ->leftJoin('users', 'ratings.user_id', '=', 'users.id')
            ->orderBy('ratings.created_at', 'desc')
            ->get();
        return Inertia::render('ReviewDashboard', [
            'reviews' => $reviews,
        ]);
    }

}
