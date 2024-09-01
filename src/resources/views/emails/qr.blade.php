<!DOCTYPE html>
<html lang="ja">

<body>
    <p>{{ $name }} 様</p>
    <p>{{ $shop }}へのご予約ありがとうございました。</p>
    <p>つきましては、来店時受付用のＱＲコードリンクをお送りさせていただきます。</p>
    <p>来店の際に、受付にてお見せください。</p>

    <a href="{{ $url }}">受付用ＱＲコードリンク</a>

    <p>それでは、当日スタッフ一同楽しみにしております。</p>

</body>

</html>