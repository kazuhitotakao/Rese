@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/admin-page.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/admin-page.css') }}">
@endif
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
                <h2 class=>User List</h2>
            </div>
        </div>
        @if (session('message'))
        <div class="alert__email">
            {!! session('message') !!}
        </div>
        @endif
        <div class="owner__inner">
            <form class="search-form" action="/owner/search" method="get">
                @csrf
                <select class="search-form__item-select" id="role-dropdown" name="role">
                    <option disabled selected>All role</option>
                    @foreach($roles_select as $role_select)
                    <option value="{{ $role_select->name }}" @if(request('role')==$role_select->name) selected @endif>
                        @php
                        $roleLabels = [
                        'admin' => '管理者',
                        'owner' => '代表者',
                        'user' => '一般',
                        ];
                        $roleName = $role_select->name;
                        @endphp
                        {{ $roleLabels[$roleName] }}
                    </option>
                    @endforeach
                </select>
                <input class="search-form__keyword-input" type="text" name="keyword" placeholder="Name or Email" value="{{request('keyword')}}">
                <input class="form__button-submit search-form__search-btn" type="submit" value="検索">
                <input class="form__button-submit search-form__reset-btn" type="submit" value="リセット" name="reset">
            </form>
        </div>
        <table class="owner__table">
            <tr class="owner__row">
                <th class="label owner__label-ID">ID</th>
                <th class="label owner__label-name">Name</th>
                <th class="label owner__label-email">Email</th>
                <th class="label owner__label-role">role</th>
                <th class="label owner__label-mail">mail</th>
            </tr>
            @php $count = 1; @endphp
            @foreach($users as $user)
            <tr class="owner__row">
                <td class="data owner__data-ID">{{ $user->id }}</td>
                <td class="data owner__data-name">{{ $user->name }}</td>
                <td class="data owner__data-email">{{ $user->email }}</td>
                @if( $roles[$count-1] == 'admin')
                <td class="data owner__data-role">管理者</td>
                @elseif( $roles[$count-1] == 'owner' )
                <td class="data owner__data-role">代表者</td>
                @else( $roles[$count-1] == 'user')
                <td class="data owner__data-role">一般</td>
                @endif
                <td class="data owner__data-mail">
                    <a class="mail__button" href="#{{ $user->id }}mail"><i class="las la-envelope"></i></a>
                    <!-- s mailモーダル -->
                    <div class="modal-wrapper" id="{{ $user->id }}mail">
                        <a href="#!" class="modal-overlay"></a>
                        <div class="modal-window">
                            <div class="modal-content">
                                <h2 class="mail-modal__title">メール送信</h2>
                                <form class="mail__form" action="/mail/admin-to-each" method="get">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <table class="mail-modal__table">
                                        <tr class="mail-modal__row">
                                            <th class="mail-modal__label">宛先</th>
                                            <td class="mail-modal__data">
                                                <div class="form__user-name">{{ $user->name }}</div>
                                            </td>
                                        </tr>
                                        <tr class="mail-modal__row">
                                            <th class="mail-modal__label">件名</th>
                                            <td class="mail-modal__data">
                                                <input class="mail__data-subject" type="text" name="subject">
                                                <div class="form__error-email">
                                                    @error('subject')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="mail-modal__row">
                                            <th class="mail-modal__label">本文</th>
                                            <td class="mail-modal__data">
                                                <textarea class="mail__data-content" type="text" name="content"></textarea>
                                                <div class="form__error-email">
                                                    @error('content')
                                                    {{ $message }}
                                                    @enderror
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <button class="mail-modal__button">送信</button>
                                </form>
                            </div>
                            <a href="#!" class="modal-close">×</a>
                        </div>
                    </div>
                    <!-- e mailモーダル -->
                </td>
            </tr>
            @php $count++ ; @endphp
            @endforeach
        </table>
    </div>
</div>

@endsection

@section('script')
<script>
    document.getElementById('role-dropdown').addEventListener('focus', function() {
        this.children[0].style.display = 'none';
    });

    document.getElementById('role-dropdown').addEventListener('blur', function() {
        if (this.value === "") {
            this.children[0].style.display = 'block';
        }
    });
</script>
@endsection
