<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Section;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    public function create(Project $project): Response
    {
        return Inertia::render('AddReview', [
            'project' => $project
        ]);
    }

}
