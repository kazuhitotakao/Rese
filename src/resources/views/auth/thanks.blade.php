@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/thanks.css') }}">
@endif
@endsection
@section('content')
<div class="mail__content">
    <div class="card">
        <h3 class="card__title">
            会員登録ありがとうございます
        </h3>
        <form class="card__button" action="/logout" method="post">
            @csrf
            <button class="card__button-submit">ログインする</button>
        </form>
    </div>
</div>
@endsection