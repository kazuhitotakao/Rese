@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
@include ('footer')
<div class="detail__container">
    <div class="shop__detail">
        <div class="detail__wrap">
            <button class="detail__btn-move" type="button" onClick="history.back()">&lt</button>
            <div class="detail__name">{{ $shop->name }}</div>
        </div>
        <div class="detail__img">
            @if(empty($shop->image))
            <img class="card__img-img" src="{{ asset('images/NoImage.png') }}" alt="image">
            @else
            <img class="card__img-img" src="{{ $imagesUrl }}" alt="image">
            @endif
        </div>
        <div class="detail__content">
            <div class="tag">
                <div class="detail__area">#{{ $shop->area }}</div>
                <div class="detail__genre">#{{ $shop->genre->name }}</div>
            </div>
        </div>
        <div class="detail__overview">
            {{ $shop->overview }}
        </div>
    </div>
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
                <input type="hidden" name="shops_id" value="{{ $shops_id }}">
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <input id="inputDate" class="form reservation__date" type="date" name="date" @if($data_flg) value="{{ $date->format('Y-m-d' )}}" @endif value="{{ old('date') }}">
                <a class="available" href="{{ route('available', ['shop_id' => $shop->id]) }}">予約空き時間検索</a>
                <div class="form__error">
                    @error('date')
                    {{ $message }}
                    @enderror
                </div>
                <select id="selectTime" class="form reservation__time" name="time">
                    <option disabled selected>時間を選択してください</option>
                    @foreach($times as $time)
                    <option value="{{ $time }}" data-time="{{ $time }}" @if($time==old('time')) selected @endif @if( $data_flg && $select_time==$time ) selected @endif>
                        {{ $time }}
                    </option>
                    @endforeach
                </select>
                <div class="form__error">
                    @error('time')
                    {{ $message }}
                    @enderror
                </div>
                <select id="selectNumber" class="form reservation__number" name="number_id">
                    <option disabled selected>人数を選択してください</option>
                    @foreach($numbers as $number)
                    <option value="{{ $number->id }}" data-number="{{ $number->number }}" @if($number->id == old('number_id')) selected @endif @if( $data_flg && $number->id==$number_id ) selected @endif>
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
                            <td class="reservation__data">{{ $shop->name }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $comment }}</td>
                        </tr>
                        <tr class="reservation__row">
                            <th class="reservation__label">Date</th>
                            <td id="tableDate" class="reservation__data"></td>
                        </tr>
                        <tr class="reservation__row">
                            <th class="reservation__label">Time</th>
                            <td id="tableTime" class="reservation__data"></td>
                        </tr>
                        <tr class="reservation__row">
                            <th class="reservation__label">Number</th>
                            <td id="tableNumber" class="reservation__data"></td>
                        </tr>
                    </table>
                </div>
                <button id="reservationButton" class="reservation__button"></button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    window.addEventListener('DOMContentLoaded', function() {
        const inputDate = document.getElementById('inputDate');
        const tableDate = document.getElementById('tableDate');
        const tableTime = document.getElementById('tableTime');
        const tableNumber = document.getElementById('tableNumber');

        tableDate.textContent = inputDate.value;
        tableTime.textContent = $("#selectTime option:selected").data("time");
        tableNumber.textContent = $("#selectNumber option:selected").data("number");

        inputDate.addEventListener('change', function() {
            tableDate.textContent = inputDate.value;
        });
        selectTime.addEventListener('change', function() {
            tableTime.textContent = $("#selectTime option:selected").data("time");
        });
        selectNumber.addEventListener('change', function() {
            tableNumber.textContent = $("#selectNumber option:selected").data("number");
        });
    });

    const button = document.getElementById('reservationButton');
    if (flgBtn) {
        button.textContent = '変更する';
    } else {
        button.textContent = '予約する';
    }
</script>
@endsection