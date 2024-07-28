@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/my-page.css') }}">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')
<script src="{{ asset('js/index.js')}}" defer></script>
<div class="my-page__wrap">
    <div class="user-name">
        <h2 class="user-name__content">{{ $user->name }}さん</h2>
    </div>
    <div class="my-page__container">
        <div class="my-page__reservation">
            <div class="reservation__title">
                <h2 class="reservation__title-content">予約状況</h2>
            </div>
            @php $count = 1; @endphp
            @foreach($reservations as $reservation)
            <div class=" wrap__table">
                <img class="clock__icon" src="{{ asset('images/clock.png') }}" alt="clock">
                <span class="table__title">予約{{ $count }}</span>
                <i class="lar la-times-circle"></i>
                <table class="reservation__table">
                    <tr class="reservation__row">
                        <th class="reservation__label">Shop</th>
                        <td class="reservation__data">{{ $shops_name[$count-1] }}</td>
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
            @php $count++ ; @endphp
            @endforeach
        </div>
        <div class="my-page__shop">
            <div class="shop__title">
                <h2 class="shop__title-content">お気に入り店舗</h2>
            </div>
            <div class="wrapper grid">
                @foreach($shops as $shop)
                @foreach($user_favorite_shops_id as $user_favorite_shop_id)
                @if($shop->id === $user_favorite_shop_id)
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
                                <i data-id="{{ $shop->id }}" class="las la-heart like-button liked"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                @endforeach
            </div>
        </div>
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