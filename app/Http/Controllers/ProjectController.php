<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
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

        $authorIds = explode(',', $project['author_id']);

        $users = $this->googleSheetService->readSheet('users');
        $userHeaders = array_shift($users);
        $users = $this->googleSheetService->convertToAssociativeArray($users, $userHeaders);

        $authors = [];
        foreach ($authorIds as $authorId) {
            $user = collect($users)->firstWhere('id', trim($authorId));
            if ($user) {
                $authors[] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                ];
            }
        }

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

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|url',
                'author_id.*' => 'required|string',
                'section_id' => 'required|string',
            ], [
                'title.required' => 'O campo título é obrigatório.',
                'description.required' => 'O campo descrição é obrigatório.',
                'image.required' => 'O campo imagem de capa é obrigatório.',
                'image.url' => 'O campo imagem deve ser uma URL válida.',
                'author_id.*.required' => 'É necessário selecionar pelo menos um autor.',
                'section_id.required' => 'O campo galeria é obrigatório.',
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
                'background_image' => $request->input('background_image') ?? 'Sem capa de fundo'
            ];

            $this->googleSheetService->writeProjectSheet($this->projectSheetName, $projectData);

            foreach ($validatedData['author_id'] as $authorId) {
                $authorRow = $this->googleSheetService->findRowById('users', $authorId);
                $authorData = $this->googleSheetService->readSheet('users')[$authorRow - 1];
                $authorName = $authorData[1];

                if ($authorRow) {
                    $authorData = [
                        'id' => uniqid(),
                        'project_id' => $projectData['id'],
                        'user_id' => $authorId,
                        'name' => $authorName,
                        'created_at' => now()->toDateTimeString(),
                        'updated_at' => now()->toDateTimeString()
                    ];
                    $this->googleSheetService->writeAuthor($authorData);
                }
            }

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

            return response()->json(['message' => 'Projeto criado com sucesso!']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
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

        $targetProject['author_id'] = explode(',', $targetProject['author_id']);
        return Inertia::render('AddProject', [
            'initialProjectData' => $targetProject,
        ])
            ->with('isEditing', true);
    }

    public function update(Request $request, $projectId): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|url',
                'author_id.*' => 'required|string',
                'section_id' => 'required|string',
            ], [
                'title.required' => 'O campo título é obrigatório.',
                'description.required' => 'O campo descrição é obrigatório.',
                'image.required' => 'O campo imagem é obrigatório.',
                'image.url' => 'O campo imagem deve ser uma URL válida.',
                'author_id.*.required' => 'É necessário selecionar pelo menos um autor.',
                'section_id.required' => 'O campo galeria é obrigatório.',
            ]);

            $projectData = [
                'id' => $request->input('id'),
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'image' => $validatedData['image'],
                'section_id' => $validatedData['section_id'],
                'author_id' => $validatedData['author_id'],
                'created_at' => $request->input('created_at'),
                'updated_at' => $request->input('updated_at') ?? now()->toDateTimeString(),
                'background_image' => $request->input('background_image') ?? 'Sem capa de fundo'
            ];

            $rowIndex = $this->googleSheetService->findRowIndexById($projectId, $this->projectSheetName);

            if ($rowIndex === null) {
                return response()->json(['message' => 'Projeto não encontrado.'], 404);
            }

            $authorIds = is_array($projectData['author_id']) ? implode(',', $projectData['author_id']) : $projectData['author_id'];
            $projectData['author_id'] = $authorIds;

            $this->googleSheetService->updateSheet($this->projectSheetName, $rowIndex, $projectData);

            return response()->json(['message' => 'Projeto atualizado com sucesso.']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
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
