<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Rating;
use Inertia\Inertia;

class RatingController extends Controller
{
    public function store(Request $request, Album $album): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $data = [
            'user_id' => $request->user()->id,
            'album_id' => $album->id,
            'rating' => $request->input('rating'),
        ];

        Rating::updateOrCreate(
            ['user_id' => $data['user_id'], 'album_id' => $data['album_id']],
            $data
        );

        $album->avg_rating = $album->ratings()->avg('rating') ?? '0.00';
        $album->save();

        return response()->json([
            'message' => 'Rating created successfully',
        ]);
    }

}
