## 【Rese（飲食店予約アプリ）】  
　～トップ画面イメージ～  
 <img width="700" alt="トップページ" src="https://github.com/user-attachments/assets/6cd33c9f-89f9-42ca-84ff-bce6984b096d">

## 【制作した目的】  
　『外部の飲食店予約サービスは手数料を取られるので自社で予約サービスを持ちたい』というクライアントの要望に応えるため。  
  
## 【機能一覧】  
## ・口コミ機能  
### 新規口コミ追加
<img width="700" alt="口コミ" src="https://github.com/user-attachments/assets/5999f500-2da9-4185-9e5c-7c829673616f">
  
- 一般ユーザーは店舗に対し口コミを追加することができる
- 口コミは「テキスト・星(1~5)・画像」で構成されている
    - テキスト：400文字以内、自由記述
    - 星(1~5)：選択式
    - 画像：jpeg、pngのみアップロード可能。非対応の拡張子はエラーメッセージを表示する
- 一般ユーザーは1店舗に対し2件以上の口コミを追加することはできない

### 口コミ編集
<img width="700" alt="口コミ編集1" src="https://github.com/user-attachments/assets/b3b86dc9-5d4d-49b9-ad20-c5f0506ab963">  
  
<img width="700" alt="口コミ編集2" src="https://github.com/user-attachments/assets/9eeea2ec-2e44-497f-9389-41acdf016a20">  
  
- 一般ユーザーは自身が追加した口コミの内容を編集することができる
- 編集画面で前回の口コミの入力値を保持することができる

### 口コミ削除
<img width="700" alt="口コミ削除_一般ユーザー" src="https://github.com/user-attachments/assets/c4469723-f22d-40e6-88f6-35d1d0ca516a">
  
<img width="700" alt="口コミ削除_管理者" src="https://github.com/user-attachments/assets/1abcd7df-868f-4089-8a83-c92b263c5c55">  
  
- 一般ユーザーは自身が追加した口コミを削除することができる
- 管理ユーザーは全ての口コミを削除することができる

|  | 一般ユーザー | 店舗ユーザー | 管理者ユーザー |
| --- | --- | --- | --- |
| 新規口コミ追加 | ○ | × | × |
| 口コミ編集 | ○ | × | × |
| 口コミ削除 | ○(*) | × | ○ |
  
## ・店舗一覧ソート機能  
<img width="700" alt="ソート" src="https://github.com/user-attachments/assets/b19d4820-e94f-42d3-9c53-9ec3ef205ae0">   
  
- 一般ユーザーは店舗一覧を並び替えることができる  
  - ランダム (※1)  
  - 評価が高い順 (※2)  
  - 評価が低い順  
  ※1 選択する度にショップの並び順が不規則に変わる  
  ※2 評価が一件もないショップの場合は、「評価が高い順」「評価が低い順」のどちらの場合でも最後尾に表示される  
         
## ・CSVインポート機能（店舗情報の新規登録 ※管理者でのログイン必須）  
 <img width="700" alt="CSVインポート" src="https://github.com/user-attachments/assets/9dea529f-9685-4e6f-8e5c-d6440d4bc16b">  
  
管理者はCSVファイルをインポートすることで、店舗情報を新規追加することができる  
【CSVファイルの記述方法】  
  1. ヘッダーの書き方（一番上の行に書く）  
     shop_name,area_id,genre_id,overview,image_path ※','で項目を区切ること（中のデータも同様）  
     それぞれ、店舗名、地域、ジャンル、店舗概要、画像URL、を表している。  
  2. area_idとgenre_idの書き方について  
     area_id  13,27,40（それぞれ、「東京都」、「大阪府」、「福岡県」）の数値を記載すること。  
     genre_id  1,2,3,4,5（それぞれ、「寿司」「焼肉」「居酒屋」「イタリアン」「ラーメン」）の数値を記載すること。  
  3. image_pathの書き方  
     AWSのS3などに保存している場合はそのまま記述する。　例）https://（以下略）  
     ネットワーク上に保存できない場合は次の方法で記述する。  
     a. 開発環境構築後に、Rese>src>public>imagesへアプロードしたい画像を移動する。  
     b. CSVファイルのimage_path欄へ /var/www/public/images/ファイル名.jpeg のようにファイル名とその拡張子以外は固定で記述すること
  4. CSVファイルの文字コードについて
     CSVファイルの文字コードはUTF-8形式で保存しておくこと。UTF-8形式以外だとCSVインポート後に文字化けを起こす。  
     ※windowsなどはデフォルトがUTF-8になっていないことが多いので要注意  
     ※UTF-8形式で保存する方法は、テキストエディタなどで名前を付けて保存し、ファイル名などを入力するウィンドウ下部で、UTF-8でエンコードする  
  5. その他の注意事項  
     ・ 項目は全て入力必須  
     ・ 店舗名は50文字以内  
     ・ 地域・ジャンルについては、項番2の数値以外受け付けない  
     ・ 店舗概要は400文字以内  
     ・ 画像URLの拡張子は、jpeg,jpg,pngのみ対応  
  
【CSVファイル記述例】  
| shop_name | area_id | genre_id | overview       | image_path                                                               |  
|-----------|---------|----------|----------------|--------------------------------------------------------------------------|  
| 花吹雪    | 13      | 1        | おいしいお寿司屋 | /var/www/public/images/sushi.jpg                                         |  
| えびす    | 40      | 1        | おいしいお寿司屋 | https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg |  
  
#### 　・ログイン機能  
#### 　・メールアドレス認証機能（メールによる本人確認）  
       Mailhogを開発環境では使用。  
       開発環境構築後に、Mailhog : http://localhost:8025/  へアクセス可能。  
       ※ 会員登録後に、Mailhogに「メールアドレスの認証」というメールが届くので、メールを開きメールアドレス認証を行う
       
#### 　・ログアウト機能  
#### 　・飲食店一覧表示＆検索機能  
#### 　・飲食店お気に入り登録機能  
#### 　・飲食店予約機能  
       予約時に、予約空き時間検索が可能（※デモ店舗には対応していない。新規登録店舗が対象）。  
       
#### 　・マイページ機能（予約内容・お気に入り飲食店一覧）  
       決済、予約変更、予約キャンセルが可能。  
       
#### 　・権限設定  
       管理者・店舗代表者・一般ユーザーに権限を分けている。  
       またゲストユーザー（権限なし）には店舗一覧のみの閲覧を可能にしている。  
       
#### 　・店舗情報作成＆予約確認画面（店舗代表者用）    
       店舗画像のアプロードと店舗情報の登録が可能。　※店舗代表者につき１店舗のみ登録可能  
       ＱＲコードリーダー起動による来店確認。  
       時間帯ごとの予約枠の上限設定。  
       予約時の時間間隔の設定。  
       
#### 　・店舗代表者設定管理画面（管理者用）  
       店舗代表者の登録が可能。  
       全ユーザーの一覧表示ができ、条件で抽出が可能。   
       
#### 　・メール送信機能（店舗代表者・管理者）  
       利用ユーザーに対して個別メールを送信できる機能を実装。  
       
#### 　・リマインダー機能  
       タスクスケジューラーを利用して、予約当日の朝に予約情報のリマインダーを送る。  

#### 　・ＱＲコード  
       予約時に利用者のメアドに受付用ＱＲコードリンクを送信する。  
       また、当日朝のリマインダーメールにも受付用ＱＲコードリンクを再度載せて送信する。  
       利用者は、店頭で店舗代表者のＱＲコードリーダーにＱＲコードをかざすことで、店舗代表者の管理画面で「来店済」になる。  
       
#### 　・決済機能（Stripeを利用）  
       アプリ内で決済機能を設けている。  
       ※Stripeのテスト環境で構築しているため、テスト時のクレジットカードは下記の公式ページのテスト用カードを使用すること。  
         テストカード：https://docs.stripe.com/testing?locale=ja-JP  
  
## 【使用技術（実行環境）】  
#### ・開発環境（Docker環境）  
    PHP		8.2.19  
    Laravel		8.83.27  
    MySQL		8.0.35  
    nginx		1.22.1  
    MailHog（メールサーバー）　  
    cron （自動実行ジョブサーバー）  
    ※開発環境においては、PCがスリープ中はcronの実行が行われないため、常にPCを起動しておくなどの対処が必要。  
  
#### ・本番環境（AWS環境）  
    EC2（バックエンド）  
    PHP		8.2.19  
    Laravel		8.83.27  
    nginx		1.22.1  
    RDS（データベース）  
    S3（ストレージ）  
    Gmail（メールサーバーとして使用）  
    cron（Amazon linux2に初期実装されている）  
  
## 【テーブル設計図】  
<img width="700" alt="CSVインポート" src="https://github.com/user-attachments/assets/7ed0219d-a56a-401f-b6d7-e7c820fbefac">  
  
## 【ER図】  
<img width="700" alt="CSVインポート" src="https://github.com/user-attachments/assets/f3a0b512-8f8c-4ad5-af26-8217b967c37d">  
  
## 【環境構築】  
### 【開発環境の構築】
### Dockerビルド  
    １　任意のディレクトリでリポジトリをクローン  
        git clone git@github.com:kazuhitotakao/Rese.git  
    ２  Reseディレクトリが作成されているのでReseディレクトリに移動  
        cd Rese  
    ３　DockerDesktopアプリを立ち上げる  
    ４　docker compose up -d --build  
    
### Laravel環境構築   
    １　【phpコンテナに入り】各種パッケージのインストール  
        docker compose exec php bash  
        composer install  
    ２　phpコンテナ内から抜けて、srcディレクリに移動し、「.env」ファイルを作成
        exit
        cd src  
        cp .env.example .env  
    ３　.envファイルに以下の環境変数を修正・追加  
       APP_NAME=Rese  
       DB_HOST=mysql  
       DB_DATABASE=laravel_db  
       DB_USERNAME=laravel_user  
       DB_PASSWORD=laravel_pass  
       MAIL_USERNAME=user  
       MAIL_PASSWORD=password  
       MAIL_FROM_ADDRESS=info@example.com  

     ※Stripe決済機能のために追加（ = 以下を各個人で設定）  
       STRIPE_PUBLIC_KEY=  
       STRIPE_SECRET_KEY=  
      （このサイトに登録し、KEYを取得する）公式サイト：https://dashboard.stripe.com/  
   
    ４　【phpコンテナに入り】アプリケーションキーの作成  
       docker compose exec php bash  
       php artisan key:generate  
  
    ５　【phpコンテナ内】マイグレーションの実行  
       php artisan migrate:fresh  
  
    ６　【phpコンテナ内】シーディングの実行  
       php artisan db:seed  
       ※初期データの作成（デモ用のデータ）  

    ７　【phpコンテナ内】パーミッション設定  
       chmod -R 775 /var/www/storage  

    ８　【phpコンテナ内】シンボリックリンク設定  
       php artisan storage:link  
       
## 【URL】  
   　【test用ユーザー】  
　　　`管理者　　　　→　Email：admin@sample.com　　Password：password`  
　　　`店舗代表者　　→　Email：owner@sample.com　　Password：password`  
　　　`一般ユーザー　→　Email：user@sample.com　　 Password：password`  
   
#### ・開発環境  
    開発環境：http://localhost/  
    phpMyAdmin：http://localhost:8080/	  
    MailHog：http://localhost:8025/  

## ・本番環境  
## ※2024.12.3現在、本番環境はEC2インスタンスを停止しているので、アクセスできない  
   ~~デプロイ済のURL：https://rese-shops.com~~  
   ※ＱＲコードリーダーでのカメラ使用の際にhttpsでの接続が必要だったため、独自ドメインでhttps接続をしている  
  
## 【その他】  
#### ・開発環境と本番環境の切り分けについて  
    .env.development（開発環境） .env.production（本番環境）で切り分けを実施  
    ※個人情報が記載されているため、git.ignoreに記載し、リモートリポジトリへはpushしていない  
  
#### ・開発環境と本番環境での主な相違点  
    開発環境ではDockerで環境構築を行っている。  
    メールアドレスの認証にMailHogを使用しており、MailHog上でメール認証を行うことが必要。  
    本番環境では、AWSで環境構築を行っている。  
    メールアドレス認証にGmailサーバーを使用しており、実際に各人のメールアドレスでメール認証を行うこと。
