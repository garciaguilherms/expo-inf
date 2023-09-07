<?php

namespace App\Http\Controllers;


use App\Models\Author;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        $projects = Project::with('authors')->get();

        return Inertia::render('Projects/Index', [
            'projects' => $projects
        ]);
    }

    public function show(Project $project): Response
    {
        $project = $project->load('authors');

        return Inertia::render('Projects/Project', [
            'project' => $project
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'author_id' => 'required|exists:users,id',
            'visibility' => 'required',
        ]);

        $project = Project::create($request->all());

        $author = User::find($request->input('author_id'));

        Author::create([
            'user_id' => $request->input('author_id'),
            'project_id' => $project->id,
            'name' => $author->name,
        ]);

        return redirect()->route('projects.index');
    }

    public function create(): Response
    {
        return Inertia::render('AddProject');
    }

    public function destroy(Project $project): void
    {
        $authors = Author::where('project_id', $project->id)->get();
        foreach ($authors as $author) {
            $author->delete();
        }

        $project->delete();
    }

}

