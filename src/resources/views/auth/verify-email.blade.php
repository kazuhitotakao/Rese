<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="/">
                    Rese
                </a>
            </div>
        </div>
    </header>
    <main>
        <div class="mail__content">
            <div class="card">
                <h3 class="card__title">
                    サービスのご利用ありがとうございます！<br>
                    登録作業はまだ完了しておりません。<br>
                    お手数ですが、以下のメール認証作業をお願いします。
                </h3>
                <div class="card__verify">
                    <ol>
                        <li>登録したメールアドレスのメールボックスを開く</li>
                        <li>「メールアドレスの認証」のメールを開く</li>
                        <li>メール本文中の「メールアドレスの認証」ボタンをクリック</li>
                    </ol>
                </div>
            </div>
        </div>
    </main>
</body>

</html>