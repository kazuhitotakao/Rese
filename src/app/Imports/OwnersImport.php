<?php

namespace App\Imports;

use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class OwnersImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    use Importable;

    public function model(array $row)
    {
        DB::beginTransaction();
        try {
            $user = new User([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => bcrypt($row['password']),
                'email_verified_at' => Carbon::now()  // 現在の日時を設定
            ]);
            $user->save();
            $user->assignRole('owner');

            $shop = new Shop([
                'name' => $row['shop_name'],
                'area' => $row['area'],
                'genre_id' => $row['genre_id'],
                'overview' => $row['overview'],
                'user_id' => $user->id  // 登録されたユーザーのID
            ]);
            $shop->save();

            DB::commit();  // トランザクションを完了して変更をデータベースに永続的に保存する
        } catch (\Exception $e) {
            DB::rollBack();  // エラーが発生した場合はロールバック
            // エラーメッセージと例外情報をログに記録
            Log::error('エラーが発生しました: ' . $e->getMessage(), ['exception' => $e]);
        }
    }

        public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',  // または 'SJIS-win' など他のエンコーディング
        ];
    }
}
