<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SectionController extends Controller
{
    public function index(): \Inertia\Response
    {
        $sections = Section::with('projects')->get();
        return Inertia::render('Sections/Index', ['sections' => $sections]);
    }

    public function allSections(): \Illuminate\Http\JsonResponse
    {
        $sections = Section::all();

        return response()->json($sections);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Sections/Create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
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

    public function edit(Section $section)
    {
        return Inertia::render('Sections/Create', [
            'initialSectionData' => $section,
        ])
            ->with('isEditing', true);
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'tags' => 'nullable',
        ]);

        $section->update($request->all());
    }

    public function destroy(Section $section): \Illuminate\Http\RedirectResponse
    {
        $section->delete();
        return redirect()->route('sections.index');
    }
}
