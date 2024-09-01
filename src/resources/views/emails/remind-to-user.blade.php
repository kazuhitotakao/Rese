<!DOCTYPE html>
<html lang="ja">

<body>
    <p>{{ $name }} 様</p>
    <p>ご予約の当日になりました。</p>
    <p>つきましては、予約の確認メールをお送りさせていただきますので、ご確認の上、ご来店をお願いします。</p>
    <p>また、受付用のQRコードリンクもお送りさせていただきます。受付にてお見せください。</p>
    <table>
        <tr>
            <th>店名</th>
            <td>{{ $shop }}</td>
        </tr>
        <tr>
            <th>日時</th>
            <td>{{ $date }}　{{ $time }}</td>
        </tr>
        <tr>
            <th>人数</th>
            <td>{{ $number }}</td>
        </tr>
    </table>

    <a href="{{ $url }}">受付用ＱＲコードリンク</a>

    <p>ご来店を心よりお待ちしております。</p>
    <p>スタッフ一同</p>

</body>

</html>