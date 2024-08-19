<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/review.css') }}">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>

<body>

    <div class="mail__content">
        <div class="card">
            <h3 class="card__title">
                アンケートにご協力ください
            </h3>
            <form class="form" action="" method="post">
                @csrf
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">評価</span>
                    </div>
                    <div class="form__group-content--review">
                        <div class="form__input--radio">
                            <input class="visually-hidden" type="radio" name="review" value="1" id="1" @if (old ('review') == '1') checked @endif>
                            <label class="form__input--radio-label" for="1">1</label>
                            <input class="visually-hidden" type="radio" name="review" value="2" id="2" @if (old ('review') == '2') checked @endif>
                            <label class="form__input--radio-label" for="2">2</label>
                            <input class="visually-hidden" type="radio" name="review" value="3" id="3" @if ( old ('review') == '3') checked @endif>
                            <label class="form__input--radio-label" for="3">3</label>
                            <input class="visually-hidden" type="radio" name="review" value="4" id="4" @if ( old ('review') == '4') checked @endif>
                            <label class="form__input--radio-label" for="4">4</label>
                            <input class="visually-hidden" type="radio" name="review" value="5" id="5" @if ( old ('review') == '5') checked @endif>
                            <label class="form__input--radio-label" for="5">5</label>
                        </div>
                        <div class="form__error">
                            @error('review')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>


                <button class="card__button-submit">回答する</button>
            </form>
        </div>
    </div>

</body>

</html>