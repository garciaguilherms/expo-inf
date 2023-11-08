<?php

namespace App\Http\Controllers;


use App\Models\Author;
use App\Models\Project;
use App\Models\ProjectSection;
use App\Models\Section;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
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
        $project = $project->load('authors', 'comments', 'comments.user');
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
            'section_id' => 'nullable|exists:sections,id',
            'visibility' => 'required',
        ]);

        $project = Project::create(array_merge($request->all(), ['created_by' => auth()->id()]));

        $section = Section::find($request->input('section_id'));

        $author = User::find($request->input('author_id'));

        Author::create([
            'user_id' => $request->input('author_id'),
            'project_id' => $project->id,
            'name' => $author->name,
        ]);

        if ($section) {
            ProjectSection::create([
                'project_id' => $project->id,
                'section_id' => $section->id
            ]);
        }


        return redirect(RouteServiceProvider::HOME);
    }

    public function create(): Response
    {
        return Inertia::render('AddProject')
            ->with('isEditing', false);
    }

    public function edit(Project $project)
    {
        return Inertia::render('AddProject', [
            'initialProjectData' => $project,
        ])
            ->with('isEditing', true);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'author_id' => 'required|exists:users,id',
            'section_id' => 'nullable|exists:sections,id',
            'visibility' => 'required',
        ]);

        $project->update($request->all());
    }

    public function destroy(Project $project): void
    {
        $authors = Author::where('project_id', $project->id)->get();
        foreach ($authors as $author) {
            $author->delete();
        }

        $project->delete();
    }

    public function search($term): \Illuminate\Http\JsonResponse
    {
        if ($term) {
            $projects = Project::with('authors')
                ->where('title', 'like', '%' . $term . '%')
                ->get();
        } else {
            $projects = Project::with('authors')->get();
        }

        return response()->json($projects);
    }

    public function ranking(): \Illuminate\Http\JsonResponse
    {
        $projects = Project::query()
            ->with('authors')
            ->orderByDesc('average_stars')
            ->get();

        return response()->json($projects);
    }

}

