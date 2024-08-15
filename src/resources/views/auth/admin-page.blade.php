@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-page.css') }}">
@endsection

@section('content')
<div class="register__content">
    <div class="card">
        <div class="register-form__heading">
            <span class="register-form__heading-text">ShopOwner Registration</span>
        </div>
        <form class="form" action="/register/owner" method="post">
            @csrf
            <div class="form__group">
                <div class="form__group-content">
                    <div class="form__input--text input__name">
                        <input type="text" name="name" placeholder="ShopOwner" value="{{ old('name') }}" />
                    </div>
                    <div class="form__error">
                        @error('name')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-content">
                    <div class="form__input--text input__email">
                        <input type="mail" name="email" placeholder="Email" value="{{ old('email') }}" />
                    </div>
                    <div class="form__error">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-content">
                    <div class="form__input--text input__password">
                        <input type="password" name="password" placeholder="Password" />
                    </div>
                    <div class="form__error">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">登録</button>
            </div>
        </form>
    </div>
    <div class="owner__content">
        <div class="owner-name__container">
            <div class="owner-name">
                <h2 class=>Owner List</h2>
            </div>
        </div>
        <div class="owner__inner">
            <form class="search-form" action="/owner/search" method="get">
                @csrf
                <input class="search-form__keyword-input" type="text" name="keyword" placeholder="名前やメールアドレスを入力してください" value="{{request('keyword')}}">
                <input class="form__button-submit search-form__search-btn" type="submit" value="検索">
                <input class="form__button-submit search-form__reset-btn" type="submit" value="リセット" name="reset">
            </form>
        </div>
        <table class="owner__table">
            <tr class="owner__row">
                <th class="label owner__label-ID">ID</th>
                <th class="label owner__label-name">ShopOwner</th>
                <th class="label owner__label-email">Email</th>
            </tr>
            @foreach($owners as $owner)
            <tr class="owner__row">
                <td class="data owner__data-ID">{{ $owner->id }}</td>
                <td class="data owner__data-name">{{ $owner->name }}</td>
                <td class="data owner__data-email">{{ $owner->email }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection