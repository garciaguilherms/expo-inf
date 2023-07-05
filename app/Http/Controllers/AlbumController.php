<?php

namespace App\Http\Controllers;


use App\Models\Album;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(): \Inertia\Response
    {
        $user = auth()->user();
        $current_user_id = auth()->id();
        $albums = Album::select('albums.*', 'ratings.rating')
            ->leftJoin('ratings', function ($join) use ($current_user_id) {
                $join->on('albums.id', '=', 'ratings.album_id')
                    ->where('ratings.user_id', '=', $current_user_id);
            })
            ->orderBy('title', 'asc')
            ->get();

        return Inertia::render('Albums/Index', [
            'albums' => $albums,
            'user' => $user,
        ]);
    }

    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $term = $request->input('searchTerm');

        $albums = Album::where('title', 'LIKE', "%$term%")
            ->orWhere('artist', 'LIKE', "%$term%")
            ->get();

        return response()->json($albums);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'release_year' => 'required',
            'cover_image' => 'required',
        ]);

        Album::create([
            'title' => $request->input('title'),
            'artist' => $request->input('artist'),
            'release_year' => $request->input('release_year'),
            'cover_image' => $request->input('cover_image'),
        ]);

        return redirect()->route('albums.index');
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('AddAlbum');
    }

}

