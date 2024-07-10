@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="attendance__container">
    <div class="attendance__comment">
        <h2><?php $user = Auth::user(); ?>{{ $user->name }}さんお疲れ様です！</h2>
    </div>
    <div class="attendance__content">
        <div class="attendance__panel">
            <form class="attendance__button" action="/attend" method="post">
                @csrf
                <button id="attend" class="attendance__button-submit" type="submit">勤務開始</button>
            </form>
            <form class="attendance__button" action="/leaving" method="post">
                @csrf
                <button id="leaving" class="attendance__button-submit" type="submit">勤務終了</button>
            </form>
        </div>
        <div class="attendance__panel">
            <form class="attendance__button" action="/break-start" method="post">
                @csrf
                <button id="break_start" class="attendance__button-submit" type="submit">休憩開始</button>
            </form>
            <form class="attendance__button" action="/break-finish" method="post">
                @csrf
                <button id="break_finish" class="attendance__button-submit" type="submit">休憩終了</button>
            </form>
        </div>
    </div>
</div>
@endsection