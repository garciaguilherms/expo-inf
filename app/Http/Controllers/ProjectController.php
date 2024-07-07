<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Inertia\Response;

class ProjectController extends Controller
{
    protected GoogleSheetService $googleSheetService;
    protected string $projectSheetName = 'projects';

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    public function index(): Response
    {
        $projects = $this->googleSheetService->readSheet($this->projectSheetName);

        return Inertia::render('Projects/Index', [
            'projects' => $projects
        ]);
    }

    public function show($projectIndex): Response
    {
        $projects = $this->googleSheetService->readSheet($this->projectSheetName);

        $projectHeaders = array_shift($projects);

        $projects = $this->googleSheetService->convertToAssociativeArray($projects, $projectHeaders);

        $project = collect($projects)->firstWhere('id', $projectIndex);

        if (!$project) {
            abort(404, 'Projeto não encontrado');
        }

        $comments = $this->googleSheetService->getRelationsById($projectIndex, 'comments', 'project_id');

        $authors = $this->googleSheetService->getRelationsById($projectIndex, 'authors', 'project_id');

        $project['authors'] = $authors;
        $project['comments'] = $comments;

        return Inertia::render('Projects/Project', [
            'project' => $project
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('AddProject')
            ->with('isEditing', false);
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|url',
            'author_id' => 'required|string',
            'section_id' => 'required|string',
        ]);

        $projectData = [
            'id' => uniqid(),
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'image' => $validatedData['image'],
            'author_id' => $validatedData['author_id'],
            'section_id' => $validatedData['section_id'],
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'background_image' => $request->input('background_image'),
        ];

        $this->googleSheetService->writeSheet($this->projectSheetName, $projectData);

        $authorId = $validatedData['author_id'];

        $authorRow = $this->googleSheetService->findRowById('users', $authorId);

        $authorData = $this->googleSheetService->readSheet('users')[$authorRow - 1];

        $authorName = $authorData[1];

        $authorData = [
                'id' => uniqid(),
                'project_id' => $projectData['id'],
                'user_id' => $authorId,
                'name' => $authorName,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString()
            ];

        $this->googleSheetService->writeAuthor($authorData);

        $sectionId = $validatedData['section_id'];

        if ($sectionId) {
            $sectionRow = $this->googleSheetService->findRowById('sections', $sectionId);
            if ($sectionRow) {
                $sectionData = [
                    'title' => $projectData['title'],
                    'section_id' => $sectionId,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString()
                ];
                $this->googleSheetService->writeSection($sectionData);
            }
        }

        return redirect()->route('projects.index');
    }

    public function edit($rowIndex): Response
    {
        $projects = $this->googleSheetService->readSheet($this->projectSheetName);

        if (empty($projects) || count($projects) < 2) {
            abort(404, 'Nenhum projeto encontrado');
        }

        $projectHeaders = array_shift($projects);

        $projects = $this->googleSheetService->convertToAssociativeArray($projects, $projectHeaders);

        $targetProject = null;
        foreach ($projects as $project) {
            if ($project['id'] === $rowIndex) {
                $targetProject = $project;
                break;
            }
        }

        if (!$targetProject) {
            abort(404, 'Projeto não encontrado');
        }

        return Inertia::render('AddProject', [
            'initialProjectData' => $targetProject,
        ])
            ->with('isEditing', true);
    }

    public function update(Request $request, $projectId): JsonResponse
    {
        $projectData = [
            'id' => $request->input('id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $request->input('image'),
            'section_id' => $request->input('section_id'),
            'author_id' => $request->input('author_id'),
            'created_at' => $request->input('created_at'),
            'updated_at' => $request->input('updated_at'),
            'created_by' => $request->input('created_by'),
            'background_image' => $request->input('background_image'),
        ];

        $projectData = array_filter($projectData, function ($value) {
            return $value !== null;
        });

        $rowIndex = $this->googleSheetService->findRowIndexById($projectId, $this->projectSheetName);

        if ($rowIndex === null) {
            return response()->json(['message' => 'Projeto não encontrado.']);
        }

        $this->googleSheetService->updateSheet($this->projectSheetName, $rowIndex, $projectData);

        return response()->json(['message' => 'Projeto atualizado com sucesso.']);
    }

    public function destroy($rowIndex): void
    {
        $row = $this->googleSheetService->findRowIndexById($rowIndex, $this->projectSheetName);

        if ($row) {
            $this->googleSheetService->deleteSheetRow($this->projectSheetName, $row);
        }
    }

    public function search(Request $request): array
    {
        $term = $request->query('term');
        return $this->googleSheetService->searchSheets($term, $this->projectSheetName);
    }
}
