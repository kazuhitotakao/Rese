@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/comment.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/comment.css') }}">
@endif
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')

<div class="comment__wrap">
    <div class="shop-name">
        <button class="btn__back" type="button" onClick="history.back()">&lt</button>
        <h2 class="shop-name__content">店名：{{ $shop->name }}</h2>
    </div>
    <div class="comment__container">
        <div class="comment__reservation">
            <div class="reservation__title">
                <h2 class="reservation__title-content">コメント一覧</h2>
            </div>
            <div class="grid">
                @php $count = 0; @endphp
                @foreach($reservations as $reservation)
                <div class=" wrap__table">
                    <span class="table__title">【コメント{{ $count + 1 }}】</span>
                    <table class="reservation__table">
                        <tr class="reservation__row">
                            <th class="reservation__label">ユーザー名</th>
                            <td class="reservation__data">{{ $users[$count] }}</td>
                        </tr>
                        <tr class="reservation__row">
                            <th class="reservation__label">評価</th>
                            <td id="tableDate" class="reservation__data">★ {{ $reservation->review }}</td>
                        </tr>
                        <tr class="reservation__row">
                            <th class="reservation__label">コメント日</th>
                            <td id="tableTime" class="reservation__data">{{ $reservation->comment_at }}</td>
                        </tr>
                        <tr class="reservation__row">
                            <th class="reservation__label">内容</th>
                            <td id="tableNumber" class="reservation__data">{{ $reservation->comment }}</td>
                        </tr>
                    </table>
                </div>
                @php $count++ ; @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection