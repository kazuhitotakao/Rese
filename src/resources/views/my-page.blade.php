@extends('layouts.app')

@section('css')
    @if (app('env') == 'local')
        <link href="{{ asset('css/my-page.css') }}" rel="stylesheet">
    @endif
    @if (app('env') == 'production')
        <link href="{{ secure_asset('css/my-page.css') }}" rel="stylesheet">
    @endif
    <link href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css"
        rel="stylesheet">
@endsection

@section('script')
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/favorite.js') }}"></script>
@endsection

@section('content')
    <div class="my-page__wrap">
        <div class="user-name">
            <h2 class="user-name__content">{{ $user->name }}さん</h2>
        </div>
        <div class="alert">
            @if (session('message'))
                <div class="alert--danger">
                    {{ session('message') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert--danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="my-page__container">
            <div class="my-page__reservation">
                <div class="reservation__title">
                    <h2 class="reservation__title-content">予約状況</h2>
                </div>
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
                        <form action="/payment/create" method="get">
                            <input name="shop_id" type="hidden" value="{{ $shops_id[$count - 1] }}">
                            <button class="payment__button"><i class="las la-yen-sign"></i></button>
                        </form>
                        <a href="#{{ $reservation->id }}change"><i class="lar la-edit"></i></a>
                        <a class="circle" href="#{{ $reservation->id }}cancel"><i class="lar la-times-circle"></i></a>
                        <table class="reservation__table">
                            <tr class="reservation__row">
                                <th class="reservation__label">Shop</th>
                                <td class="reservation__data">{{ $shops_name[$count - 1] }}</td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Date</th>
                                <td class="reservation__data" id="tableDate">{{ $reservation->date->format('Y-m-d') }}</td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Time</th>
                                <td class="reservation__data" id="tableTime">{{ $select_times[$count - 1] }}</td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Number</th>
                                <td class="reservation__data" id="tableNumber">{{ $numbers[$count - 1] }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- s キャンセル確認モーダル -->
                    <div class="modal-wrapper" id="{{ $reservation->id }}cancel">
                        <a class="modal-overlay" href="#!"></a>
                        <div class="modal-window">
                            <div class="modal-content">
                                <h2 class="cancel__title">予約のキャンセル</h2>
                                <div class="cancel__text">
                                    <span>予約をキャンセルしてもよろしいですか？</span>
                                </div>
                                <div class="cancel__btn">
                                    <form class="cancel__form" action="/cancel" method="post">
                                        @csrf
                                        <input name="reservation_id" type="hidden" value="{{ $reservation->id }}">
                                        <button class="modal__btn" type="submit">はい</button>
                                    </form>
                                    <a class="modal__btn--a" href="#!">いいえ</a>
                                </div>
                            </div>
                            <a class="modal-close" href="#!">×</a>
                        </div>
                    </div>
                    <!-- e キャンセル確認モーダル -->

                    <!-- s 予約変更モーダル -->
                    <div class="modal-wrapper" id="{{ $reservation->id }}change">
                        <a class="modal-overlay" href="#!"></a>
                        <div class="modal-window">
                            <div class="modal-content">
                                <h2 class="reservation-modal__title">予約の変更</h2>
                                <form class="reservation__form" action="/reserve/change" method="post">
                                    @csrf
                                    <input name="reservation_id" type="hidden" value="{{ $reservation->id }}">
                                    <input name="shop_id" type="hidden" value="{{ $shops_id[$count - 1] }}">
                                    <a class="available"
                                        href="{{ route('available', ['shop_id' => $shops_id[$count - 1]]) }}">予約空き時間検索</a>
                                    <table class="reservation-modal__table">
                                        <tr class="reservation-modal__row">
                                            <th class="reservation-modal__label">Shop</th>
                                            <td class="reservation-modal__data">
                                                <div class="form form__shop-name">{{ $shops_name[$count - 1] }}</div>
                                            </td>
                                        </tr>
                                        <tr class="reservation-modal__row">
                                            <th class="reservation-modal__label">Date</th>
                                            <td class="reservation-modal__data">
                                                <input class="form reservation__date" name="date" type="date"
                                                    value="{{ $reservation->date->format('Y-m-d') }}">
                                                <div class="form__error">
                                                    @error('date')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="reservation-modal__row">
                                            <th class="reservation-modal__label">Time</th>
                                            <td class="reservation-modal__data">
                                                <select class="form reservation__time" name="time">
                                                    <option disabled selected>時間を選択してください</option>
                                                    @foreach ($times[$count - 1] as $time)
                                                        <option value="{{ $time }}"
                                                            @if ($time == $select_times[$count - 1]) selected @endif>
                                                            {{ $time }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class=" form__error">
                                                    @error('time')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="reservation-modal__row">
                                            <th class="reservation-modal__label">Number</th>
                                            <td class="reservation-modal__data">
                                                <select class="form reservation__number" name="number_id">
                                                    <option disabled selected>人数を選択してください</option>
                                                    @foreach ($numbers_all as $number)
                                                        <option value="{{ $number->id }}"
                                                            @if ($number->id == $numbers_id[$count - 1]) selected @endif>
                                                            {{ $number->number }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="form__error">
                                                    @error('number_id')
                                                        {{ $message }}
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <button class="reservation-modal__button">変更する</button>
                                </form>
                            </div>
                            <a class="modal-close" href="#!">×</a>
                        </div>
                    </div>
                    <!-- e 予約変更モーダル -->
                    @php $count++ ; @endphp
                @endforeach

            </div>
            <div class="my-page__shop">
                <div class="shop__title">
                    <h2 class="shop__title-content">お気に入り店舗</h2>
                </div>
                <div class="wrapper grid">
                    @php $count = 1; @endphp
                    @foreach ($shops as $shop)
                        @foreach ($user_favorite_shops_id as $user_favorite_shop_id)
                            @if ($shop->id === $user_favorite_shop_id)
                                <div class="shop__card">
                                    <div class="card__img">
                                        @if (empty($shop->image))
                                            @if (app('env') == 'local')
                                                <img class="card__img-img" src="{{ asset('images/NoImage.png') }}"
                                                    alt="image">
                                            @endif
                                            @if (app('env') == 'production')
                                                <img class="card__img-img" src="{{ secure_asset('images/NoImage.png') }}"
                                                    alt="image">
                                            @endif
                                        @else
                                            <img class="card__img-img" src="{{ $imagesUrl[$count - 1] }}" alt="image">
                                        @endif
                                    </div>
                                    <div class="card__content">
                                        <div class="card__name">{{ $shop->name }}</div>
                                        <div class="tag">
                                            <div class="card__area">#{{ $shop->area->name }}</div>
                                            <div class="card__genre">#{{ $shop->genre->name }}</div>
                                            <div class="form__wrap">
                                                <form class="detail__form"
                                                    action="{{ route('detail', ['shop_id' => $shop->id]) }}"
                                                    method="get">
                                                    <button class="btn detail__button">詳しく見る</button>
                                                </form>
                                                <i class="las la-heart like-button liked"
                                                    data-id="{{ $shop->id }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @php $count++ ; @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
