<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\OwnersImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        Excel::import(new OwnersImport, $request->file('file'));

        return back()->with('success', 'CSVファイルをインポートしました');
    }
}
