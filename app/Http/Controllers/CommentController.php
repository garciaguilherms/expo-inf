<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected GoogleSheetService $googleSheetService;
    protected string $commentSheetName = 'comments';

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    public function store(Request $request, $projectId): RedirectResponse
    {
        $request->validate([
            'text' => 'required',
        ]);

        $commentData = [
            'id' => uniqid(),
            'project_id' => $projectId,
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->name,
            'text' => $request->input('text'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $this->googleSheetService->writeSheet($this->commentSheetName, $commentData);

        return back();
    }
}
