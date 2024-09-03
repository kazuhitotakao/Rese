@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/done.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/done.css') }}">
@endif
@endsection
@section('content')
<div class="done__content">
    <div class="card">
        <h3 class="card__title">
            ご予約ありがとうございます
        </h3>
        <form class="card__button" action="/done-back" method="post">
            @csrf
            <input type="hidden" name="shops_id" value="{{ $shops_id }}">
            <button class="card__button-submit">戻る</button>
        </form>
    </div>
</div>
@endsection