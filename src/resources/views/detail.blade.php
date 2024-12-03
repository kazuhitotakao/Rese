@extends('layouts.app')

@section('css')
    @if (app('env') == 'local')
        <link href="{{ asset('css/detail.css') }}" rel="stylesheet">
    @endif
    @if (app('env') == 'production')
        <link href="{{ secure_asset('css/detail.css') }}" rel="stylesheet">
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/detail.js') }}"></script>
@endsection

@section('content')
    @include ('footer')
    <div class="detail__container">
        @can('user')
            {{-- 一般ユーザーは口コミの追加・編集・削除可能 --}}
            @if ($review_flg)
                {{-- 口コミを投稿済みの場合 --}}
                <div class="shop__detail">
                    <div class="detail__img detail__img--reviewed">
                        @if (empty($shop->image))
                            @if (app('env') == 'local')
                                <img class="card__image-img card__image-img--reviewed" src="{{ asset('images/NoImage.png') }}"
                                    alt="image">
                            @endif
                            @if (app('env') == 'production')
                                <img class="card__image-img card__image-img--reviewed"
                                    src="{{ secure_asset('images/NoImage.png') }}" alt="image">
                            @endif
                        @else
                            <img class="card__image-img card__image-img--reviewed" src="{{ $imagesUrl }}" alt="image">
                        @endif
                    </div>
                    <div class="detail__content">
                        <div class="tag">
                            <div class="detail__area">#{{ $shop->area->name }}</div>
                            <div class="detail__genre">#{{ $shop->genre->name }}</div>
                        </div>
                    </div>
                    <div class="detail__overview">
                        {{ $shop->overview }}
                    </div>
                    <a class="detail__review-link-all"
                        href="{{ route('shop.comments.index', ['shop_id' => $shop->id]) }}">全ての口コミ情報</a>
                    <hr class="horizontal-line">
                    <div class="detail__review-wrapper">
                        <a class="detail__review-link" href="{{ route('reviews.edit', ['shop_id' => $shop->id]) }}">口コミを編集</a>
                        <form action="{{ route('reviews.destroy', ['shop_id' => $shop->id]) }}" method="POST"
                            onsubmit="return confirm('このコメントを削除してもよろしいですか？');">
                            @csrf
                            @method('DELETE')
                            <button class="detail__review-button">口コミを削除</button>
                        </form>
                    </div>
                    <div class="detail__review-rating">
                        <span class="star5_rating" data-rate="{{ $review_rating }}"></span>
                    </div>
                    <p class="detail__review-comment">{{ $review_comment }}</p>
                    <div class="detail__review-image">
                        @foreach ($review_image_data as $image_data)
                            <div id="image-container-{{ $image_data['id'] }}">
                                <img src="{{ $image_data['url'] }}" alt="Uploaded image">
                            </div>
                        @endforeach
                    </div>
                    <hr class="horizontal-line horizontal-line--bottom">
                </div>
            @else
                {{-- 口コミ未投稿の場合 --}}
                <div class="shop__detail">
                    <div class="detail__wrap">
                        <a class="detail__btn-move" href="/">&lt</a>
                        <div class="detail__name">{{ $shop->name }}</div>
                    </div>
                    <div class="detail__img">
                        @if (empty($shop->image))
                            @if (app('env') == 'local')
                                <img class="card__image-img" src="{{ asset('images/NoImage.png') }}" alt="image">
                            @endif
                            @if (app('env') == 'production')
                                <img class="card__image-img" src="{{ secure_asset('images/NoImage.png') }}" alt="image">
                            @endif
                        @else
                            <img class="card__image-img" src="{{ $imagesUrl }}" alt="image">
                        @endif
                    </div>
                    <div class="detail__content">
                        <div class="tag">
                            <div class="detail__area">#{{ $shop->area->name }}</div>
                            <div class="detail__genre">#{{ $shop->genre->name }}</div>
                        </div>
                    </div>
                    <div class="detail__overview">
                        {{ $shop->overview }}
                    </div>
                    <div class="detail__review">
                        <a class="detail__review-link"
                            href="{{ route('reviews.show', ['shop_id' => $shop->id]) }}">口コミを投稿する</a>
                    </div>
                </div>
            @endif
        @endcan
        @can('owner')
            {{-- 店舗ユーザーは口コミ関連はなにもできない --}}
            <div class="shop__detail">
                <div class="detail__wrap">
                    <a class="detail__btn-move" href="/">&lt</a>
                    <div class="detail__name">{{ $shop->name }}</div>
                </div>
                <div class="detail__img">
                    @if (empty($shop->image))
                        @if (app('env') == 'local')
                            <img class="card__image-img" src="{{ asset('images/NoImage.png') }}" alt="image">
                        @endif
                        @if (app('env') == 'production')
                            <img class="card__image-img" src="{{ secure_asset('images/NoImage.png') }}" alt="image">
                        @endif
                    @else
                        <img class="card__image-img" src="{{ $imagesUrl }}" alt="image">
                    @endif
                </div>
                <div class="detail__content">
                    <div class="tag">
                        <div class="detail__area">#{{ $shop->area->name }}</div>
                        <div class="detail__genre">#{{ $shop->genre->name }}</div>
                    </div>
                </div>
                <div class="detail__overview">
                    {{ $shop->overview }}
                </div>
            </div>
        @endcan
        @can('register')
            {{-- 管理者ユーザーは口コミ追加はできないが全権削除が可能 --}}
            <div class="shop__detail">
                <div class="detail__wrap">
                    <a class="detail__btn-move" href="/">&lt</a>
                    <div class="detail__name">{{ $shop->name }}</div>
                </div>
                <div class="detail__img detail__img--reviewed">
                    @if (empty($shop->image))
                        @if (app('env') == 'local')
                            <img class="card__image-img card__image-img--reviewed" src="{{ asset('images/NoImage.png') }}"
                                alt="image">
                        @endif
                        @if (app('env') == 'production')
                            <img class="card__image-img card__image-img--reviewed"
                                src="{{ secure_asset('images/NoImage.png') }}" alt="image">
                        @endif
                    @else
                        <img class="card__image-img card__image-img--reviewed" src="{{ $imagesUrl }}" alt="image">
                    @endif
                </div>
                <div class="detail__content">
                    <div class="tag">
                        <div class="detail__area">#{{ $shop->area->name }}</div>
                        <div class="detail__genre">#{{ $shop->genre->name }}</div>
                    </div>
                </div>
                <div class="detail__overview">
                    {{ $shop->overview }}
                </div>
                <a class="detail__review-link-all"
                    href="{{ route('shop.comments.index', ['shop_id' => $shop->id]) }}">全ての口コミ情報（管理者用 ※全件削除可）</a>
            </div>
        @endcan
        <div class="shop__reservation">
            <div class="reservation__wrap-form">
                <h2 class="reservation__title">予約</h2>
                @if (session('message'))
                    <div class="alert__info">
                        {{ session('message') }}
                    </div>
                @endif
                <form class="reservation__form" action="/reserve" method="post">
                    @csrf
                    <input name="shops_id" type="hidden" value="{{ $shops_id }}">
                    <input name="shop_id" type="hidden" value="{{ $shop->id }}">
                    <input class="form reservation__date" id="inputDate" name="date" type="date"
                        value="{{ $data_flg ? $date->format('Y-m-d') : old('date') }}">
                    <a class="available" href="{{ route('available', ['shop_id' => $shop->id]) }}">予約空き時間検索</a>
                    <div class="form__error">
                        @error('date')
                            {{ $message }}
                        @enderror
                    </div>
                    <select class="form reservation__time" id="selectTime" name="time">
                        <option disabled selected>時間を選択してください</option>
                        @foreach ($times as $time)
                            <option data-time="{{ $time }}" value="{{ $time }}"
                                @if ($time == old('time')) selected @endif
                                @if ($data_flg && $select_time == $time) selected @endif>
                                {{ $time }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form__error">
                        @error('time')
                            {{ $message }}
                        @enderror
                    </div>
                    <select class="form reservation__number" id="selectNumber" name="number_id">
                        <option disabled selected>人数を選択してください</option>
                        @foreach ($numbers as $number)
                            <option data-number="{{ $number->number }}" value="{{ $number->id }}"
                                @if ($number->id == old('number_id')) selected @endif
                                @if ($data_flg && $number->id == $number_id) selected @endif>
                                {{ $number->number }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form__error">
                        @error('number_id')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class=" wrap__table">
                        <table class="reservation__table">
                            <tr class="reservation__row">
                                <th class="reservation__label">Shop</th>
                                <td class="reservation__data">
                                    {{ $shop->name }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $comment }}</td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Date</th>
                                <td class="reservation__data" id="tableDate"></td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Time</th>
                                <td class="reservation__data" id="tableTime"></td>
                            </tr>
                            <tr class="reservation__row">
                                <th class="reservation__label">Number</th>
                                <td class="reservation__data" id="tableNumber"></td>
                            </tr>
                        </table>
                    </div>
                    <button class="reservation__button" id="reservationButton"></button>
                </form>
            </div>
        </div>
    </div>
@endsection
