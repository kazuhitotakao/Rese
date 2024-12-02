<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ShopsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $import = new ShopsImport();  // インスタンスの生成
        DB::beginTransaction(); // トランザクション開始

        try {
            Excel::import($import, $request->file('file'));

            if (!empty($import->getErrors())) {
                // エラーメッセージが存在する場合
                DB::rollBack(); // トランザクションをロールバック
                return back()->with('error_messages', $import->getErrors()); // エラーメッセージをセッションに保存してリダイレクト
            }
            DB::commit(); // トランザクションを完了して変更をデータベースにに保存する
            return back()->with('import_success', 'CSVファイルをインポートしました');
        } catch (\Exception $e) {
            DB::rollBack(); // 例外が発生した場合はロールバック
            // エラーメッセージと例外情報をログに記録
            Log::error('エラーが発生しました: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
}
