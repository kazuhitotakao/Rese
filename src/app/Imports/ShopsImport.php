<?php

namespace App\Imports;

use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ShopsImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    use Importable;
    public $errors = []; // エラーメッセージを格納する配列

    public function model(array $row)
    {
        $validator = Validator::make($row, $this->rules(), $this->messages());

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->errors[] = $error;
            }
            return null;
        }

        // $path を初期化
        $path = null;

        // 画像のパスがHTTP URLかローカルパスかを確認
        $image_path = $row['image_path'];
        if (strpos($image_path, 'http') === 0) {
            // HTTP URLの場合、そのまま$pathに設定
            $path = $image_path;
        } else {
            // ローカルパスから画像を読み取り
            if (file_exists($image_path)) {
                $contents = file_get_contents($image_path);
                $path = 'images/' . basename($image_path);
                // Laravelのストレージに保存
                Storage::disk('public')->put($path, $contents);
            }
        }

        $shop = new Shop([
            'name' => $row['shop_name'],
            'area' => $row['area'],
            'genre_id' => $row['genre_id'],
            'overview' => $row['overview'],
            'image' => $path
        ]);
        return $shop;
    }

    // エラーメッセージを返すメソッド
    public function getErrors()
    {
        return $this->errors;
    }

    private function rules()
    {
        return [
            'shop_name' => 'required|string|max:50',
            'area' => 'required|in:東京都,大阪府,福岡県',
            'genre_id' => 'required|in:1,2,3,4,5', // 1:寿司 2:焼肉 3:居酒屋 4:イタリアン 5:ラーメン
            'overview' => 'required|string|max:400',
            'image_path' => 'required|image_extension'
        ];
    }

    private function messages()
    {
        return [
            'shop_name.required' => '店舗名は必須です。',
            'shop_name.max' => '店舗名は最大50文字までです。',
            'area.required' => '地域は必須です。',
            'area.in' => '地域は東京都・大阪府・福岡県のみ有効です。',
            'genre_id.required' => 'ジャンルは必須です。',
            'genre_id.in' => '無効なジャンルが入力されています。',
            'overview.required' => '店舗概要は必須です。',
            'overview.max' => '店舗概要は最大400文字までです。',
            'image_path.required' => '画像URLは必須です。',
            'image_path.image_extension' => '画像はjpegまたはpng形式でお願いします。'
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',  // または 'SJIS-win' など他のエンコーディング
        ];
    }
}
