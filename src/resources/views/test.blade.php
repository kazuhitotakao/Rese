<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/test.css') }}">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>

<body>
        <button class="like__form-button" type="submit">
            <i id="likeButton" class="lar la-heart like-button"></i>
        </button>

    <script>
        document.getElementById('likeButton').addEventListener('click', function() {
            if (this.classList.contains('liked')) {
                this.classList.remove('liked');
                this.classList.replace('las', 'lar');
            } else {
                this.classList.add('liked');
                this.classList.replace('lar', 'las');
            }
        });
    </script>
</body>

</html>