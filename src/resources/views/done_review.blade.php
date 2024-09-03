@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/done-review.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/done-review.css') }}">
@endif
@endsection
@section('content')
<div class="done__content">
    <div class="card">
        <h3 class="card__title">
            ご回答ありがとうございます
        </h3>
        <form class="card__button" action="/" method="get">
            <button class="card__button-submit">ホーム画面</button>
        </form>
    </div>
</div>
@endsection