@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner-page.css') }}">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')
@include ('footer')
<script src="{{ asset('js/index.js')}}" defer></script>
<div class="owner-page__wrap">
    <div class="user-name">
        <h2 class="user-name__content">店舗代表者：{{ $user->name }}さん</h2>
    </div>
    <div class="owner-page__container">
        <div class="owner-page__shop">
            <div class="shop__title">
                <span class="shop__title-content">店舗情報</span>
                @if($reviews_count > 0)
                <span class="shop__review-content">{{ $review_average }}</span>
                <form class="shop__comment-form" action="/comment" method="get">
                    <button class="shop__comment-content"><i class="las la-comment"></i>コメント</button>
                </form>
                @endif
            </div>
            <div class="wrapper">
                <div class="shop__card">
                    <form action="/image-upload" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="image__content">
                            <div class="card__img">
                                @if(empty($shop->image))
                                @if(empty($image->path))
                                <img class="card__img-img" src="{{ asset('images/NoImage.png') }}" alt="image">
                                @else
                                <img class="card__img-img" src="{{ Storage::url($image->path) }}" alt="image">
                                @endif
                                @else
                                <img class="card__img-img" src="{{ $imageUrl }}" alt="image">
                                @endif
                            </div>
                            <div class="wrap__img">
                                @if (session('messageImg'))
                                <div class=" alert__info">
                                    {!! session('messageImg') !!}
                                </div>
                                @endif
                                <div class="form__group-file">
                                    <input type="file" class="form-file" name="image">
                                </div>
                                <button id="imageButton" type="submit" class="form__button-image"></button>
                            </div>
                        </div>
                    </form>
                    <form action="/shop/saveOrUpdate" method="post">
                        @csrf
                        <div class="card__content">
                            <div class="form__group">
                                <div class="form__group-title">
                                    <span class="card__content-label">店名</span>
                                </div>
                                <div class="form__group-content">
                                    @if(empty($shop->name))
                                    <input name="name" type="text" class="form card__name" placeholder="店名を記入してください" value="{{ old('name') }}">
                                    @else
                                    <input name=" name" type="text" class="form card__name" value="{{ $shop->name }}">
                                    @endif
                                    <div class="form__error">
                                        @error('name')
                                        {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form__group">
                                <div class="form__group-title">
                                    <span class="card__content-label">地域</span>
                                </div>
                                <div class="form__group-content">
                                    @if(empty($shop->area))
                                    <select class="form select__form" id="pref-dropdown" name="area">
                                        <option disabled selected value="">登録する地域を選択してください</option>
                                        @foreach(config('pref') as $pref_id => $name)
                                        <option value="{{ $name }}" @if(old('area')==$name) selected @endif>
                                            {{ $name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <select class="form select__form" id="pref-dropdown" name="area">
                                        @foreach(config('pref') as $pref_id => $name)
                                        <option value="{{ $name }}" @if( $shop->area == $name) selected @endif>
                                            {{ $name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @endif
                                    <div class="form__error">
                                        @error('area')
                                        {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form__group">
                                <div class="form__group-title">
                                    <span class="card__content-label">ジャンル</span>
                                </div>
                                <div class="form__group-content">
                                    @if(empty($shop->genre_id))
                                    <select class="form select__form" id="genre-dropdown" name="genre_id">
                                        <option disabled selected value="">登録するジャンルを選択してください</option>
                                        @foreach($genres as $genre)
                                        <option value="{{ $genre->id }}" @if(old('genre_id')==$genre->id) selected @endif>
                                            {{ $genre->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <select class="form select__form" id="genre-dropdown" name="genre_id">
                                        @foreach($genres as $genre)
                                        <option value="{{ $genre->id }}" @if( $shop->genre_id == $genre->id ) selected @endif>
                                            {{ $genre->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @endif
                                    <div class="form__error">
                                        @error('genre_id')
                                        {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form__group">
                                <div class="form__group-title">
                                    <span class="card__content-label">概要</span>
                                </div>
                                <div class="form__group-content">
                                    @if(empty($shop->overview))
                                    <textarea name="overview" class="form card__overview" placeholder="概要を記入してください"></textarea>
                                    @else
                                    <textarea name="overview" class="form card__overview">{{ $shop->overview }}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form__button">
                            <button class="form__button-submit" type="submit" name="action" value="register">登録</button>
                            <button class="form__button-submit" type="submit" name="action" value="update">更新</button>
                        </div>
                    </form>
                    @if (session('message'))
                    <div class=" alert__info">
                        {!! session('message') !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="owner-page__reservation">
            <div class="reservation__title">
                <h2 class="reservation__title-content">予約情報</h2>
            </div>
            <div class="alert--danger">
                @error('subject')
                {{ $message }}
                @enderror
            </div>
            <div class="alert--danger">
                @error('content')
                {{ $message }}
                @enderror
            </div>
            @if (session('messageEmail'))
            <div class="alert__email">
                {!! session('messageEmail') !!}
            </div>
            @endif
            @php $count = 1; @endphp
            @foreach($reservations as $reservation)
            <div class=" wrap__table">
                <img class="clock__icon" src="{{ asset('images/clock.png') }}" alt="clock">
                <span class="table__title">予約{{ $count }}</span>
                <a class="mail__button" href="#{{ $reservation->id }}mail"><i class="las la-envelope"></i></a>
                <table class="reservation__table">
                    <tr class="reservation__row">
                        <th class="reservation__label">Name</th>
                        <td class="reservation__data">{{ $users_name[$count-1] }}</td>
                    </tr>
                    <tr class="reservation__row">
                        <th class="reservation__label">Date</th>
                        <td id="tableDate" class="reservation__data">{{ $reservation->date->format('Y-m-d') }}</td>
                    </tr>
                    <tr class="reservation__row">
                        <th class="reservation__label">Time</th>
                        <td id="tableTime" class="reservation__data">{{ $times[$count-1]->format('H:i') }}</td>
                    </tr>
                    <tr class="reservation__row">
                        <th class="reservation__label">Number</th>
                        <td id="tableNumber" class="reservation__data">{{ $numbers[$count-1] }}</td>
                    </tr>
                </table>
            </div>

            <!-- s mailモーダル -->
            <div class="modal-wrapper" id="{{ $reservation->id }}mail">
                <a href="#!" class="modal-overlay"></a>
                <div class="modal-window">
                    <div class="modal-content">
                        <h2 class="mail-modal__title">メール送信</h2>
                        <form class="mail__form" action="/mail/shop-to-user" method="get">
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                            <table class="mail-modal__table">
                                <tr class="mail-modal__row">
                                    <th class="mail-modal__label">宛先</th>
                                    <td class="mail-modal__data">
                                        <div class="form__user-name">{{ $users_name[$count-1] }}</div>
                                    </td>
                                </tr>
                                <tr class="mail-modal__row">
                                    <th class="mail-modal__label">件名</th>
                                    <td class="mail-modal__data">
                                        <input class="mail__data-subject" type="text" name="subject">
                                        <div class="form__error">
                                            @error('subject')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                                <tr class="mail-modal__row">
                                    <th class="mail-modal__label">本文</th>
                                    <td class="mail-modal__data">
                                        <textarea class="mail__data-content" type="text" name="content"></textarea>
                                        <div class="form__error">
                                            @error('content')
                                            {{ $message }}
                                            @enderror
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <button class="mail-modal__button">送信</button>
                        </form>
                    </div>
                    <a href="#!" class="modal-close">×</a>
                </div>
            </div>
            <!-- e mailモーダル -->
            @php $count++ ; @endphp
            @endforeach
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    button = document.getElementById('imageButton');
    if (flgBtn) {
        button.textContent = '更新';
    } else {
        button.textContent = '登録';
    }
</script>
@endsection