## 【Rese（飲食店予約アプリ）】  
　～トップ画面イメージ～  
 <img width="700" alt="トップページ" src="https://github.com/user-attachments/assets/83af9e52-9f89-491d-8548-5ff231bd4f5e">

## 【制作した目的】  
　『外部の飲食店予約サービスは手数料を取られるので自社で予約サービスを持ちたい』というクライアントの要望に応えるため。  
  
## 【機能一覧】  
#### 　・ログイン機能  
#### 　・メールアドレス認証機能（メールによる本人確認）  
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
<img src="https://github.com/user-attachments/assets/c61c1c55-c1b2-42e4-b319-5c7ef7e36849" alt="owner-page2" width="700"/>  
  
　　　　店舗画像のアプロードと店舗情報の登録が可能。　※店舗代表者につき１店舗のみ登録可能  
　　　　評価★の数やコメント内容閲覧可能。  
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
#### 　・評価機能  
　　　　予約時間を経過したら、利用者のメールアドレスにメールが自動送信され、お店の５段階評価とコメントを依頼する。
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
　　Laravel	8.83.27  
　　MySQL	8.0.35  
　　nginx	1.22.1  
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
 ![table](https://github.com/user-attachments/assets/1cefeaf7-ff8a-451c-ab79-38c0fb170468)
  
## 【ER図】  
![まとめ](https://github.com/user-attachments/assets/b061c9ae-2daa-42d3-8467-399969d84057)
  
## 【環境構築】  
### ※開発環境のみ記載※  
### Dockerビルド  
　１　git clone git@github.com:kazuhitotakao/Rese.git  
　２　DockerDesktopアプリを立ち上げる  
　３　docker compose up -d --build  
   
### Laravel環境構築  
　１　docker compose exec php bash  
　２　composer install  
　３　「.env.example」ファイルを 「.env」ファイルに命名を変更  
 　　　または、新しく.envファイルを作成  
  
　４　.envファイルに以下の環境変数を修正・追加  
　　　APP_NAME=Rese  
　　　DB_HOST=mysql  
　　　DB_DATABASE=laravel_db  
　　　DB_USERNAME=laravel_user  
　　　DB_PASSWORD=laravel_pass  
　　　MAIL_USERNAME=user  
　　　MAIL_PASSWORD=password  
　　　MAIL_FROM_ADDRESS=info@example.com  

　　　※Stripe決済機能のために追加  
　　　STRIPE_PUBLIC_KEY=  
　　　STRIPE_SECRET_KEY=  
　　　（参考）公式サイト：https://dashboard.stripe.com/  
   
　５　アプリケーションキーの作成  
　　　php artisan key:generate  
  
　６　マイグレーションの実行  
　　　php artisan migrate:fresh  
  
　７　シーディングの実行  
　　　php artisan db:seed  
　　　※テストデータが作成される 

　８　パーミッション設定  
　　　chmod -R 777 /var/www/storage  

　９　シンボリックリンク設定  
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
#### ・本番環境
　　デプロイ済のURL：https://rese-shops.com  
　　※ＱＲコードリーダーでのカメラ使用の際にhttpsでの接続が必要だったため、独自ドメインでhttps接続をしている  
　　※2024.9.20現在、本番環境は停止している
## 【その他】  
#### ・開発環境と本番環境の切り分けについて  
　　.env.development（開発環境） .env.production（本番環境）で切り分けを実施  
　　※個人情報が記載されているため、git.ignoreに記載し、リモートリポジトリへはpushしていない  
  
#### ・開発環境と本番環境での主な相違点  
　　開発環境ではDockerで環境構築を行っている。  
　　メールアドレスの認証にMailHogを使用しており、MailHog上でメール認証を行うことが必要。  
　　本番環境では、AWSで環境構築を行っている。  
　　メールアドレス認証にGmailサーバーを使用しており、実際に各人のメールアドレスでメール認証を行うこと。

