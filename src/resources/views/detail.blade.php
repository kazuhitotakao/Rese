@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail__container">
    <div class="shop__detail">
        <div class="detail__wrap">
            <form class="detail__form" action="/detail/back" method="get">
                @csrf
                <button class="detail__btn-move" type="submit">&lt</button>
            </form>
            <div class="detail__name">{{ $shop->name }}</div>
        </div>
        <div class="detail__img">
            <img src="{{ $shop->image }}" alt="image">
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
            <form class="reservation__form" action="/reserve" method="post">
                @csrf
                <input type="hidden" name="shop_id" value="{{ $shop->id }}" readonly />
                <input id="inputDate" class="form reservation__date" type="date" name="date" value="{{ $date->format('Y-m-d') }}">
                <select id="selectTime" class="form reservation__time" name="time_id">
                    @foreach($times as $time)
                    @php
                    $formattedTime = (new DateTime($time->time))->format('H:i');
                    @endphp
                    <option value="{{ $time->id }}" data-time="{{ $formattedTime }}" @if( $time->id==$time_id ) selected @endif>
                        {{ $formattedTime }}
                    </option>
                    @endforeach
                </select>
                <select id="selectNumber" class="form reservation__number" name="number_id">
                    @foreach($numbers as $number)
                    <option value="{{ $number->id }}" @if( $number->id==$number_id ) selected @endif>
                        {{ $number->number }}
                    </option>
                    @endforeach
                </select>
                <div class="wrap__table">
                    <table class="reservation__table">
                        <tr class="reservation__row">
                            <th class="reservation__label">Shop</th>
                            <td class="reservation__data">{{ $shop->name }}</td>
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
                <button class="reservation__button">予約する</button>

            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    window.addEventListener('load', function() {
        const inputDate = document.getElementById('inputDate');
        const selectNumber = document.getElementById('selectNumber');
        const tableDate = document.getElementById('tableDate');
        const tableTime = document.getElementById('tableTime');
        const tableNumber = document.getElementById('tableNumber');

        tableDate.textContent = inputDate.value;
        tableTime.textContent = $("#selectTime option:selected").data("time");
        tableNumber.textContent = `${selectNumber.value}人`;



        inputDate.addEventListener('change', function() {
            tableDate.textContent = inputDate.value;
        });
        selectTime.addEventListener('change', function() {
            tableTime.textContent = $("#selectTime option:selected").data("time");
        });
        selectNumber.addEventListener('change', function() {
            tableNumber.textContent = `${selectNumber.value}人`;
        });
    });
</script>
@endsection