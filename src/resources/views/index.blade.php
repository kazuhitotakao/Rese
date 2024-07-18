@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<script src="{{ asset('js/index.js')}}" defer></script>
<div class="shop__container">
    <div class="shop__inner">
        <form class="search-form" action="/search" method="get">
            @csrf
            <select class="search-form__item-select" id="pref-dropdown" name="pref">
                <option disabled selected>All area</option>
                @foreach(config('pref') as $pref_id => $name)
                <option value="{{ $name }}" @if( request('pref')==$name ) selected @endif>
                    {{ $name }}
                </option>
                @endforeach
            </select>
            <select class="search-form__item-select" id="genre-dropdown" name="genre_id">
                <option disabled selected>All genre</option>
                @foreach($genres as $genre)
                <option value="{{ $genre->id }}" @if( request('genre_id')==$genre->id ) selected @endif>
                    {{ $genre->name }}
                </option>
                @endforeach
            </select>
            <input class="search-form__keyword-input" type="text" name="keyword" placeholder="Search..." value="{{request('keyword')}}">
            <input class="btn" type="submit" value="検索">
            <input class="btn" type="submit" value="リセット" name="reset">
        </form>
    </div>
    <div class="wrapper grid">
    @foreach($shops as $shop)
        <div class="shop__card">
            <div class="card__img">
                <img src="{{ $shop->image }}" alt="image">
            </div>
            <div class="card__content">
                <div class="card__name">{{ $shop->name }}</div>
                <div class="tag">
                    <div class="card__area">{{ $shop->area }}</div>
                    <div class="card__genre">{{ $shop->genre->name }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection