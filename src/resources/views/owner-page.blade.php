@extends('layouts.app')

@section('css')
    @if (app('env') == 'local')
        <link href="{{ asset('css/owner-page.css') }}" rel="stylesheet">
    @endif
    @if (app('env') == 'production')
        <link href="{{ secure_asset('css/owner-page.css') }}" rel="stylesheet">
    @endif
    <link href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css"
        rel="stylesheet">
@endsection

@section('script')
    <script src="{{ asset('js/index.js') }}" defer></script>
    <script src="{{ asset('js/shop_image_upload.js') }}" defer></script>
@endsection

@section('content')
    @include ('footer')
    <div class="owner-page__wrap">
        <div class="user-name">
            <h2 class="user-name__content">店舗代表者：{{ $user->name }}さん</h2>
        </div>
        <div class="owner-page__container">
            <div class="owner-page__shop">
                <div class="shop__title">
                    <span class="shop__title-content">店舗情報</span>
                    @if (!empty($shop))
                        <form class="shop__setting-form" action="/setting" method="get">
                            <button class="shop__setting-content"> <i class="las la-cog"></i>設定</button>
                        </form>
                    @endif
                </div>
                <div class="wrapper">
                    <div class="shop__card">
                        <form action="/image-upload" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="image__content">
                                <div class="card__img">
                                    <!-- 開発環境の場合 -->
                                    @if (app('env') == 'local')
                                        @if (empty($shop->image))
                                            @if (empty($image->path))
                                                <img class="card__img-img" src="{{ asset('images/NoImage.png') }}"
                                                    alt="image">
                                            @else
                                                <img class="card__img-img" src="{{ Storage::url($image->path) }}"
                                                    alt="image">
                                            @endif
                                        @else
                                            <img class="card__img-img" src="{{ $imageUrl }}" alt="image">
                                        @endif
                                    @endif
                                    <!-- 本番環境の場合 -->
                                    @if (app('env') == 'production')
                                        @if (empty($shop->image))
                                            @if (empty($image->path))
                                                <img class="card__img-img" src="{{ secure_asset('images/NoImage.png') }}"
                                                    alt="image">
                                            @else
                                                <img class="card__img-img" src="{{ $image->path }}" alt="image">
                                            @endif
                                        @else
                                            <img class="card__img-img" src="{{ $imageUrl }}" alt="image">
                                        @endif
                                    @endif
                                </div>
                                <div class="wrap__img">
                                    @if (session('messageImg'))
                                        <div class=" alert__info">
                                            {!! session('messageImg') !!}
                                        </div>
                                    @endif
                                    <div class="form__group-file">
                                        <input class="form-file" name="image" type="file">
                                    </div>
                                    <button class="form__button-image" id="imageButton" type="submit"></button>
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
                                        @if (empty($shop->name))
                                            <input class="form card__name" name="name" type="text"
                                                value="{{ old('name') }}" placeholder="店名を記入してください">
                                        @else
                                            <input class="form card__name" name=" name" type="text"
                                                value="{{ $shop->name }}">
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
                                        @if (empty($shop->area_id))
                                            <select class="form select__form" id="pref-dropdown" name="area_id">
                                                <option value="" disabled selected>登録する地域を選択してください</option>
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}"
                                                        @if (old('area_id') == $area->id) selected @endif>
                                                        {{ $area->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select class="form select__form" id="pref-dropdown" name="area">
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}"
                                                        @if (old('area_id') == $area->id) selected @endif>
                                                        {{ $area->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                        <div class="form__error">
                                            @error('area_id')
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
                                        @if (empty($shop->genre_id))
                                            <select class="form select__form" id="genre-dropdown" name="genre_id">
                                                <option value="" disabled selected>登録するジャンルを選択してください</option>
                                                @foreach ($genres as $genre)
                                                    <option value="{{ $genre->id }}"
                                                        @if (old('genre_id') == $genre->id) selected @endif>
                                                        {{ $genre->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select class="form select__form" id="genre-dropdown" name="genre_id">
                                                @foreach ($genres as $genre)
                                                    <option value="{{ $genre->id }}"
                                                        @if ($shop->genre_id == $genre->id) selected @endif>
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
                                        @if (empty($shop->overview))
                                            <textarea class="form card__overview" name="overview" placeholder="概要を記入してください"></textarea>
                                        @else
                                            <textarea class="form card__overview" name="overview">{{ $shop->overview }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form__button">
                                <button class="form__button-submit" name="action" type="submit"
                                    value="register">登録</button>
                                <button class="form__button-submit" name="action" type="submit"
                                    value="update">更新</button>
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
                <a class="qr" href="/qr"><i class="las la-qrcode"></i>来店確認</a>
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
                @foreach ($reservations as $reservation)
                    <div class=" wrap__table">
                        @if (app('env') == 'local')
                            <img class="clock__icon" src="{{ asset('images/clock.png') }}" alt="clock">
                        @endif
                        @if (app('env') == 'production')
                            <img class="clock__icon" src="{{ secure_asset('images/clock.png') }}" alt="clock">
                        @endif
                        <span class="table__title">予約{{ $count }}</span>
                        @if (!empty($checks_in[$count - 1]))
                            <span class="check_in">来店済</span>
                        @endif
                        <a class="mail__button" href="#{{ $reservation->id }}mail"><i class="las la-envelope"></i></a>
                        <table class="reservation__table">
                            <tr class="reservation__row">
                                <th class="reservation__label">Name</th>
                                <td class="reservation__data">{{ $users_name[$count - 1] }}</td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Date</th>
                                <td class="reservation__data" id="tableDate">{{ $reservation->date->format('Y-m-d') }}
                                </td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Time</th>
                                <td class="reservation__data" id="tableTime">{{ $times[$count - 1] }}</td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Number</th>
                                <td class="reservation__data" id="tableNumber">{{ $numbers[$count - 1] }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- s mailモーダル -->
                    <div class="modal-wrapper" id="{{ $reservation->id }}mail">
                        <a class="modal-overlay" href="#!"></a>
                        <div class="modal-window">
                            <div class="modal-content">
                                <h2 class="mail-modal__title">メール送信</h2>
                                <form class="mail__form" action="/mail/shop-to-user" method="get">
                                    <input name="reservation_id" type="hidden" value="{{ $reservation->id }}">
                                    <table class="mail-modal__table">
                                        <tr class="mail-modal__row">
                                            <th class="mail-modal__label">宛先</th>
                                            <td class="mail-modal__data">
                                                <div class="form__user-name">{{ $users_name[$count - 1] }}</div>
                                            </td>
                                        </tr>
                                        <tr class="mail-modal__row">
                                            <th class="mail-modal__label">件名</th>
                                            <td class="mail-modal__data">
                                                <input class="mail__data-subject" name="subject" type="text">
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
                                                <textarea class="mail__data-content" name="content" type="text"></textarea>
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
                            <a class="modal-close" href="#!">×</a>
                        </div>
                    </div>
                    <!-- e mailモーダル -->
                    @php $count++ ; @endphp
                @endforeach
            </div>
        </div>
    </div>

@endsection
