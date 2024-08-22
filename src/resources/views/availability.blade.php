@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/availability.css') }}">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')

<div class="available__wrap">
    <div class="shop-name">
        <button class="btn__back" type="button" onClick="history.back()">&lt</button>
        <h2 class="shop-name__content">店名：{{ $shop->name }}</h2>
    </div>
    <div class="alert">
        @if (session('message'))
        <div class="alert__info">
            {!! session('message') !!}
        </div>
        @endif
    </div>
    <div class="available__container">
        <div class="available__title">
            <h2 class="title-content">予約空き時間検索</h2>
        </div>
        <form action="/available-search" method="get">
            <input id="inputDate" class="form" type="date" name="date" value="{{ request('date') }}">
            <input type="hidden" name="shop_id" value="{{ $shop_id }}">
            <button class="available">検索</button>
        </form>
        <div class=" wrap__table">
            <table>
                <tr>
                    <th>10時台</th>
                    <th>11時台</th>
                    <th>12時台</th>
                    <th>13時台</th>
                    <th>14時台</th>
                    <th>15時台</th>
                    <th>16時台</th>
                </tr>
                <tr>
                    <td><input class="input__time" type="text" name="mark10" value="{{ $mark10 }}"></td>
                    <td><input class="input__time" type="text" name="mark11" value="{{ $mark11 }}"></td>
                    <td><input class="input__time" type="text" name="mark12" value="{{ $mark12 }}"></td>
                    <td><input class="input__time" type="text" name="mark13" value="{{ $mark13 }}"></td>
                    <td><input class="input__time" type="text" name="mark14" value="{{ $mark14 }}"></td>
                    <td><input class="input__time" type="text" name="mark15" value="{{ $mark15 }}"></td>
                    <td><input class="input__time" type="text" name="mark16" value="{{ $mark16 }}"></td>
                </tr>
                <tr>
                    <th>17時台</th>
                    <th>18時台</th>
                    <th>19時台</th>
                    <th>20時台</th>
                    <th>21時台</th>
                    <th>22時台</th>
                    <th>23時台</th>
                </tr>
                <tr>
                    <td><input class="input__time" type="text" name="mark17" value="{{ $mark17 }}"></td>
                    <td><input class="input__time" type="text" name="mark18" value="{{ $mark18 }}"></td>
                    <td><input class="input__time" type="text" name="mark19" value="{{ $mark19 }}"></td>
                    <td><input class="input__time" type="text" name="mark20" value="{{ $mark20 }}"></td>
                    <td><input class="input__time" type="text" name="mark21" value="{{ $mark21 }}"></td>
                    <td><input class="input__time" type="text" name="mark22" value="{{ $mark22 }}"></td>
                    <td><input class="input__time" type="text" name="mark23" value="{{ $mark23 }}"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection