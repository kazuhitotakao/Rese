@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')
@include ('footer')
<script src="{{ asset('js/index.js')}}" defer></script>
<div class="shop__container">
    <div class="shop__inner">
        <form class="search-form" action="/search" method="get">
            @csrf
            <select class="search-form__item-select" id="pref-dropdown" name="pref">
                <option disabled selected>All area</option>
                @foreach(config('pref') as $pref_id => $name)
                <option value="{{ $name }}" @if( $search['pref']==$name ) selected @endif>
                    {{ $name }}
                </option>
                @endforeach
            </select>
            <select class="search-form__item-select" id="genre-dropdown" name="genre_id">
                <option disabled selected>All genre</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}" @if( $search['genre_id']==$genre->id ) selected @endif>
                    {{ $genre->name }}
                </option>
                @endforeach
            </select>
            <input class="search-form__keyword-input" type="text" name="keyword" placeholder="Search..." value="{{ $search['keyword'] }}">
            <input class="btn" type="submit" value="検索">
            <input class="btn" type="submit" value="リセット" name="reset">
        </form>
    </div>
    <div class="wrapper grid">
        @foreach($shops as $shop)
        @php
        $isMatched = false;
        @endphp
        <div class="shop__card">
            <div class="card__img">
                <img src="{{ $shop->image }}" alt="image">
            </div>
            <div class="card__content">
                <div class="card__name">{{ $shop->name }}</div>
                <div class="tag">
                    <div class="card__area">#{{ $shop->area }}</div>
                    <div class="card__genre">#{{ $shop->genre->name }}</div>
                    <div class="form__wrap">
                        <form class="detail__form" action="{{ route('detail', ['shop_id' => $shop->id]) }}" method="post">
                            @csrf
                            <button class="btn detail__button">詳しく見る</button>
                        </form>
                        @foreach($common_shops_id as $common_shop_id)
                        @if($shop->id === $common_shop_id)
                        @php
                        $isMatched = true;
                        @endphp
                        <i data-id="{{ $shop->id }}" class="las la-heart like-button liked"></i>
                        @php
                        break;
                        @endphp
                        @endif
                        @endforeach
                        @if($isMatched == false)
                        <i data-id="{{ $shop->id }}" class="lar la-heart like-button"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.like-button').on('click', function() {
            const shop_id = $(this).data('id');
            if (this.classList.contains('liked')) {
                this.classList.remove('liked');
                this.classList.replace('las', 'lar');
            } else {
                this.classList.add('liked');
                this.classList.replace('lar', 'las');
            }
            $.ajax({
                url: '/favorite',
                type: 'POST',
                data: {
                    shop_id: shop_id
                },
                dataType: "json",
            }).done(function(res) {
                console.log(res);
            }).fail(function() {
                alert('通信の失敗をしました');
            });
        });
    });
</script>
@endsection