# 店舗情報のCSVインポート

管理者でログインし、メニューの「Admin Page」から「Import CSV」を使用します。入力条件は[src/app/Imports/ShopsImport.php](../src/app/Imports/ShopsImport.php)で定義しています。

## ファイル形式

UTF-8・カンマ区切りで保存し、先頭行に次のヘッダーを記載します。全項目が必須です。

```csv
shop_name,area_id,genre_id,overview,image_path
花吹雪,13,1,おいしいお寿司屋,/var/www/public/images/sushi.jpg
えびす,40,2,おいしい焼肉屋,/var/www/public/images/yakiniku.jpg
```

この例は同梱の店舗写真を使い、Docker環境で実行する想定です。

| 項目 | 入力条件 |
| --- | --- |
| `shop_name` | 店舗名、50文字以内 |
| `area_id` | 13＝東京都、27＝大阪府、40＝福岡県 |
| `genre_id` | 1＝寿司、2＝焼肉、3＝居酒屋、4＝イタリアン、5＝ラーメン |
| `overview` | 店舗概要、400文字以内 |
| `image_path` | JPEG・JPG・PNGの画像URL、またはPHPコンテナから読み取れる画像ファイルの絶対パス |

## AWSを使わずに画像を登録する場合

1. 画像をリポジトリの`src/public/images/`に置きます。
2. CSVの`image_path`には`/var/www/public/images/ファイル名.jpg`のように、PHPコンテナ内のパスを記載します。
3. 管理画面でCSVを選択してインポートします。

ローカルファイルはアプリの公開ストレージへコピーされます。[環境構築](../README.md#環境構築)の`php artisan storage:link`を実行しておく必要があります。

AWSのS3を使用することは必須ではありません。外部の画像URLを指定する場合は、そのURLから実際に画像を取得できることを確認してください。

## インポート時の処理

入力条件に違反する行がある場合はエラーを表示し、トランザクションをロールバックする実装です。処理は[src/app/Http/Controllers/ImportController.php](../src/app/Http/Controllers/ImportController.php)を参照してください。
