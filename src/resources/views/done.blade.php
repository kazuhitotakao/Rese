@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css') }}">
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