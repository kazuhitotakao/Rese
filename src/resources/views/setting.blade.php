@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/setting.css') }}">
@endif
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')

<div class="setting__wrap">
    <div class="shop-name">
        <button class="btn__back" type="button" onClick="history.back()">&lt</button>
        <h2 class="shop-name__content">店名：{{ $shop->name }}</h2>
    </div>
    <div class="setting__container">
        <form action="/setting/save" method="post">
            @csrf
            <div class=" wrap__table">
                <div class="setting__title">
                    <h2 class="title-content">時間枠設定</h2>
                </div>
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
                        <td><input class="input__time" type="number" name="time10" value="{{ $time10[0] }}"></td>
                        <td><input class="input__time" type="number" name="time11" value="{{ $time11[0] }}"></td>
                        <td><input class="input__time" type="number" name="time12" value="{{ $time12[0] }}"></td>
                        <td><input class="input__time" type="number" name="time13" value="{{ $time13[0] }}"></td>
                        <td><input class="input__time" type="number" name="time14" value="{{ $time14[0] }}"></td>
                        <td><input class="input__time" type="number" name="time15" value="{{ $time15[0] }}"></td>
                        <td><input class="input__time" type="number" name="time16" value="{{ $time16[0] }}"></td>
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
                        <td><input class="input__time" type="number" name="time17" value="{{ $time17[0] }}"></td>
                        <td><input class="input__time" type="number" name="time18" value="{{ $time18[0] }}"></td>
                        <td><input class="input__time" type="number" name="time19" value="{{ $time19[0] }}"></td>
                        <td><input class="input__time" type="number" name="time20" value="{{ $time20[0] }}"></td>
                        <td><input class="input__time" type="number" name="time21" value="{{ $time21[0] }}"></td>
                        <td><input class="input__time" type="number" name="time22" value="{{ $time22[0] }}"></td>
                        <td><input class="input__time" type="number" name="time23" value="{{ $time23[0] }}"></td>
                    </tr>
                </table>
            </div>
            <div class=" wrap__table">
                <div class="setting__title">
                    <h2 class="title-content">予約時間間隔設定</h2>
                </div>
                <table>
                    <tr>
                        <th>間隔</th>
                    </tr>
                    <tr>
                        <td><input class="input__interval" type="number" name="interval" value="{{ $interval }}"></td>
                    </tr>
                </table>
                <button class="setting__button">設定する</button>
            </div>
        </form>
    </div>
</div>
@endsection