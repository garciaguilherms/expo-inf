<?php

namespace App\Http\Controllers;

use Revolution\Google\Sheets\Facades\Sheets;

class GoogleSheetController extends Controller
{
    public function index()
    {
        $values = Sheets::spreadsheet('1H8zzCuEFcMa4o-1QbTCXhyNWXVgHeEr_fi5xVNmNSGw')->sheet('Sheet 1')->all();
    }
}
