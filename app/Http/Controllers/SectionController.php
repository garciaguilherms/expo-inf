<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SectionController extends Controller
{
    public function index(): Response
    {
        $sections = Section::with('projects')
            ->with('projects.authors')
            ->get();

        return Inertia::render('Sections/Index', ['sections' => $sections]);
    }

    public function allSections(): JsonResponse
    {
        $sections = Section::all();

        return response()->json($sections);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Sections/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'tags' => 'nullable',
        ]);

        $request->merge(['creator_id' => auth()->id()]);

        Section::create(array_merge($request->all(), ['created_by' => auth()->id()]));

        return redirect()->route('sections.index');
    }

    public function edit(Section $section): Response
    {
        return Inertia::render('Sections/Create', [
            'initialSectionData' => $section,
        ])
            ->with('isEditing', true);
    }

    public function update(Request $request, Section $section): void
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'tags' => 'nullable',
        ]);

        $section->update($request->all());
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();
        return redirect()->route('sections.index');
    }
}
