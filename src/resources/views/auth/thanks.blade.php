<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="">
                    Rese
                </a>
            </div>
        </div>
    </header>
    <main>
        <div class="mail__content">
            <div class="card">
                <h3 class="card__title">
                    会員登録ありがとうございます
                </h3>
                <!-- <div class="card__button">
                    <a class="card__button-submit" href="/login">ログインする</a>
                </div> -->
                <form class="card__button" action="/logout" method="post">
                    @csrf
                    <button class="card__button-submit">ログインする</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>