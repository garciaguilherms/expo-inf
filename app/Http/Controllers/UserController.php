<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected GoogleSheetService $googleSheetService;
    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }
    public function index(): JsonResponse
    {
        $sheetData = $this->googleSheetService->readSheet('users');

        $headers = array_shift($sheetData);

        $users = array_map(function($row) use ($headers) {
            return array_combine($headers, $row);
        }, $sheetData);

        return response()->json($users);
    }


}
