<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, Project $project): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'text' => 'required',
        ]);

        $comment = new Comment([
            'text' => $request->text,
            'user_id' => auth()->id(),
            'project_id' => $project->id,
        ]);

        $project->comments()->save($comment);

        return back();
    }

}
