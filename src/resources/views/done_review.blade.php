@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done-review.css') }}">
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