@extends('layouts.app')

@section('css')
    @if (app('env') == 'local')
        <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    @endif
    @if (app('env') == 'production')
        <link href="{{ secure_asset('css/index.css') }}" rel="stylesheet">
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/favorite.js') }}"></script>
@endsection

@section('content')
    @include ('footer')
    <div class="shop__container">
        <div class="shop__inner">
            <form class="search-form" action="/search" method="get">
                @can('user')
                    <select class="search-form__item-select search-form__item-select--sort" id="sort-dropdown" name="sort"
                        onchange="this.form.submit()">
                        <option disabled selected>並び替え：評価高/低</option>
                        <option value="random" @if ($sort === 'random') selected @endif>ランダム</option>
                        <option value="high_rating" @if ($sort === 'high_rating') selected @endif>評価が高い順</option>
                        <option value="low_rating" @if ($sort === 'low_rating') selected @endif>評価が低い順</option>
                    </select>
                @endcan
                <div class="search-form__group">
                    <select class="search-form__item-select search-form__item-select--pref" id="pref-dropdown"
                        name="area_id" onchange="this.form.submit()">
                        <option disabled selected>All area</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @if ($search['area_id'] == $area->id) selected @endif>
                                {{ $area->name }}
                            </option>
                        @endforeach
                    </select>
                    <select class="search-form__item-select search-form__item-select--genre" id="genre-dropdown"
                        name="genre_id" onchange="this.form.submit()">
                        <option disabled selected>All genre</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}" @if ($search['genre_id'] == $genre->id) selected @endif>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                    <input class="search-form__keyword-input" name="keyword" type="text" value="{{ $search['keyword'] }}"
                        placeholder="Search..." onchange="this.form.submit()">
                </div>
            </form>
        </div>
        <div class="wrapper grid">
            @php $count = 1; @endphp
            @foreach ($shops as $shop)
                @php
                    $isMatched = false;
                @endphp
                <div class="shop__card">
                    <div class="card__img">
                        @if (empty($shop->image))
                            @if (app('env') == 'local')
                                <img class="card__img-img" src="{{ asset('images/NoImage.png') }}" alt="image">
                            @endif
                            @if (app('env') == 'production')
                                <img class="card__img-img" src="{{ secure_asset('images/NoImage.png') }}" alt="image">
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
                                <form class="detail__form" action="{{ route('detail', ['shop_id' => $shop->id]) }}"
                                    method="get">
                                    <button class="btn detail__button">詳しく見る</button>
                                </form>
                                @can('register-only')
                                    @foreach ($common_shops_id as $common_shop_id)
                                        @if ($shop->id === $common_shop_id)
                                            @php
                                                $isMatched = true;
                                            @endphp
                                            <i class="las la-heart like-button liked" data-id="{{ $shop->id }}"></i>
                                            @php
                                                break;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if ($isMatched == false)
                                        <i class="lar la-heart like-button" data-id="{{ $shop->id }}"></i>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                @php $count++ ; @endphp
            @endforeach
        </div>
    </div>
@endsection
