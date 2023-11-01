<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $rating = Rating::where('user_id', auth()->user()->id)
            ->where('project_id', $project->id)
            ->first();

        if (!$rating) {
            $rating = new Rating;
            $rating->user_id = auth()->user()->id;
            $rating->project_id = $project->id;
            $rating->type = 1;
        }

        $rating->stars = $request->stars;
        $rating->save();

        $averageRating = Rating::where('project_id', $project->id)->avg('stars');

        $project->average_stars = $averageRating;
        $project->save();

        return back();
    }

    public function userRating(Request $request, Project $project)
    {
        $userId = $request->user()->id;
        $projectId = $project->id;

        $rating = Rating::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();

        return response()->json([
            'rating' => $rating ? $rating->stars : 0
        ]);
    }

}
