<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Services\GoogleSheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SectionController extends Controller
{
    protected GoogleSheetService $googleSheetService;
    protected string $sectionSheetName = 'sections';

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    public function index(): Response
    {
        $sections = $this->googleSheetService->readSheet($this->sectionSheetName);
        $sectionsHeader = array_shift($sections);
        $sections = $this->googleSheetService->convertToAssociativeArray($sections, $sectionsHeader);

        foreach ($sections as &$section) {
            $sectionId = $section['id'];
            $projects = $this->googleSheetService->getRelationsById($sectionId, 'projects', 'section_id');
            $section['projects'] = $projects;
        }

        return Inertia::render('Sections/Index', [
            'sections' => $sections
        ]);
    }

    public function show($sectionIndex): Response
    {
        $sections = $this->googleSheetService->readSheet($this->sectionSheetName);

        $sectionHeaders = array_shift($sections);
        $sections = $this->googleSheetService->convertToAssociativeArray($sections, $sectionHeaders);

        $section = collect($sections)->firstWhere('id', $sectionIndex);

        if (!$section) {
            abort(404, 'Galeria não encontrada');
        }

        // Obter todos os projetos
        $projects = $this->googleSheetService->getProjects();
        // Filtrar projetos para encontrar os que pertencem a esta Galeria
        $sectionProjects = array_filter($projects, function ($project) use ($sectionIndex) {
            return $project['section_id'] === $sectionIndex;
        });

        $section['projects'] = $sectionProjects;

        return Inertia::render('Sections/Show', [
            'section' => $section
        ]);
    }

    public function allSections(): JsonResponse
    {
        $sections = $this->googleSheetService->readSheet($this->sectionSheetName);
        $sectionsHeaders = array_shift($sections);

        $sections = $this->googleSheetService->convertToAssociativeArray($sections, $sectionsHeaders);

        return response()->json($sections);
    }

    public function create(): Response
    {
        return Inertia::render('Sections/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'tags' => 'nullable',
        ]);

        $sectionData = [
            'id' => uniqid(),
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'tags' => $validatedData['tags'],
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => auth()->user()->id,
        ];

        $this->googleSheetService->writeSheet($this->sectionSheetName, $sectionData);

        return redirect()->route('sections.index');
    }

    public function edit($rowIndex): Response
    {
        $sections = $this->googleSheetService->readSheet($this->sectionSheetName);

        if (empty($sections) || count($sections) < 2) {
            abort(404, 'Nenhuma galeria encontrada');
        }

        $sectionHeaders = array_shift($sections);

        $sections = $this->googleSheetService->convertToAssociativeArray($sections, $sectionHeaders);

        $targetSection = null;
        foreach ($sections as $section) {
            if ($section['id'] === $rowIndex) {
                $targetSection = $section;
                break;
            }
        }

        if (!$targetSection) {
            abort(404, 'Galeria não encontrada');
        }

        return Inertia::render('Sections/Create', [
            'initialSectionData' => $targetSection
        ])->with('isEditing', true);
    }

    public function update(Request $request, $sectionId): JsonResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'required|string',
        ]);

        $rowIndex = $this->googleSheetService->findRowIndexById($sectionId, $this->sectionSheetName);

        if ($rowIndex === null) {
            return response()->json(['message' => 'Galeria não encontrada.']);
        }

        $sectionData = [
            'id' => $sectionId,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'tags' => $validatedData['tags'],
            'updated_at' => now()->toDateTimeString(),
        ];

        $this->googleSheetService->updateSheet($this->sectionSheetName, $rowIndex, $sectionData);

        return response()->json(['message' => 'Galeria atualizada com sucesso.']);
    }

    public function destroy($rowIndex): RedirectResponse
    {
        $row = $this->googleSheetService->findRowIndexById($rowIndex, $this->sectionSheetName);

        if ($row) {
            $this->googleSheetService->deleteSheetRow($this->sectionSheetName, $row);
        }
        return redirect()->route('sections.index');
    }

    public function search(Request $request): array
    {
        $term = $request->query('term');
        return $this->googleSheetService->searchSectionsWithProjects($term);
    }
}
