@extends('layouts.app')

@section('css')
    @if (app('env') == 'local')
        <link href="{{ asset('css/admin-page.css') }}" rel="stylesheet">
    @endif
    @if (app('env') == 'production')
        <link href="{{ secure_asset('css/admin-page.css') }}" rel="stylesheet">
    @endif
@endsection

@section('content')
    <div class="register__content">
        <div class="card-wrapper">
            <div class="card">
                <div class="register-form__heading">
                    <span class="register-form__heading-text">ShopOwner Registration</span>
                </div>
                <form class="form" action="/register/owner" method="post">
                    @csrf
                    <div class="form__group">
                        <div class="form__group-content">
                            <div class="form__input--text input__name">
                                <input name="name" type="text" value="{{ old('name') }}" placeholder="ShopOwner" />
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
                                <input name="email" type="mail" value="{{ old('email') }}" placeholder="Email" />
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
                                <input name="password" type="password" placeholder="Password" />
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
            <h2 class="import-csv__title">Import CSV</h2>
            @if (session('import_success'))
                <div class="import__success">
                    {!! session('import_success') !!}
                </div>
            @endif
            @if (session('error_messages'))
                <div class="alert alert-danger custom-alert">
                    <ul>
                        @foreach (session('error_messages') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="import-csv" action="{{ route('import.csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input class="form-file" name="file" type="file" required>
                <button class="form__button-csv" type="submit">インポート</button>
            </form>
            <p class="import-csv__description">※店舗情報をCSVファイルから追加する。</p>
        </div>
        <div class="owner__content">
            <div class="owner-name__container">
                <div class="owner-name">
                    <h2>User List</h2>
                </div>
            </div>
            @if (session('success'))
                <div class="alert__email">
                    {!! session('success') !!}
                </div>
            @endif
            <div class="owner__inner">
                <form class="search-form" action="/owner/search" method="get">
                    @csrf
                    <select class="search-form__item-select" id="role-dropdown" name="role">
                        <option disabled selected>All role</option>
                        @foreach ($roles_select as $role_select)
                            <option value="{{ $role_select->name }}" @if (request('role') == $role_select->name) selected @endif>
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
                    <input class="search-form__keyword-input" name="keyword" type="text"
                        value="{{ request('keyword') }}" placeholder="Name or Email">
                    <input class="form__button-submit search-form__search-btn" type="submit" value="検索">
                    <input class="form__button-submit search-form__reset-btn" name="reset" type="submit" value="リセット">
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
                @foreach ($users as $user)
                    <tr class="owner__row">
                        <td class="data owner__data-ID">{{ $user->id }}</td>
                        <td class="data owner__data-name">{{ $user->name }}</td>
                        <td class="data owner__data-email">{{ $user->email }}</td>
                        @if ($roles[$count - 1] == 'admin')
                            <td class="data owner__data-role">管理者</td>
                        @elseif($roles[$count - 1] == 'owner')
                            <td class="data owner__data-role">代表者</td>
                        @else( $roles[$count-1] == 'user')
                            <td class="data owner__data-role">一般</td>
                        @endif
                        <td class="data owner__data-mail">
                            <a class="mail__button" href="#{{ $user->id }}mail"><i class="las la-envelope"></i></a>
                            <!-- s mailモーダル -->
                            <div class="modal-wrapper" id="{{ $user->id }}mail">
                                <a class="modal-overlay" href="#!"></a>
                                <div class="modal-window">
                                    <div class="modal-content">
                                        <h2 class="mail-modal__title">メール送信</h2>
                                        <form class="mail__form" action="/mail/admin-to-each" method="get">
                                            <input name="user_id" type="hidden" value="{{ $user->id }}">
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
                                                        <input class="mail__data-subject" name="subject" type="text">
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
                                                        <textarea class="mail__data-content" name="content" type="text"></textarea>
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
                                    <a class="modal-close" href="#!">×</a>
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
