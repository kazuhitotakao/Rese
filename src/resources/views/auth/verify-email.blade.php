@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection
@section('content')
<div class="mail__content">
    <div class="card">
        <h3 class="card__title">
            サービスのご利用ありがとうございます！<br>
            登録作業はまだ完了しておりません。<br>
            お手数ですが、以下のメール認証作業をお願いします。
        </h3>
        <div class="card__verify">
            <ol>
                <li>登録したメールアドレスのメールボックスを開く</li>
                <li>「メールアドレスの認証」のメールを開く</li>
                <li>メール本文中の「メールアドレスの認証」ボタンをクリック</li>
            </ol>
        </div>
    </div>
</div>
@endsection

