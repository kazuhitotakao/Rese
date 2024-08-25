<!DOCTYPE html>
<html lang="ja">

<body>
    <p>{{ $name }} 様</p>
    <p>{{ $shop }}へのご予約ありがとうございました。</p>
    <p>つきましては、下記の受付用QRコードをお送りさせていただきます。</p>
    <p>来店の際に店頭でお見せください。</p>

    <div style="margin: 1rem;">
        {!! QrCode::generate($reservation_id); !!}
    </div>

    <p>それでは、当日スタッフ一同楽しみにしております。</p>

</body>

</html>