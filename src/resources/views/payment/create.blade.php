@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/payment-create.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/payment-create.css') }}">
@endif
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endsection

@section('content')
<div class="container">
    @if (session('flash_alert'))
    <div class="alert alert-danger">{{ session('flash_alert') }}</div>
    @elseif(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    <div class="d-flex p-5 justify-content-center">
        <div class="col-6 card">
            <div class="card-header">Stripe決済</div>
            <div class="card-body">
                <form id="card-form" action="{{ route('payment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <div>
                        <label for="card_number">カード番号</label>
                        <div id="card-number" class="form-control"></div>
                    </div>
                    <div>
                        <label for="card_expiry">有効期限</label>
                        <div id="card-expiry" class="form-control"></div>
                    </div>
                    <div>
                        <label for="card-cvc">セキュリティコード</label>
                        <div id="card-cvc" class="form-control"></div>
                    </div>
                    <div id="card-errors" class="text-danger"></div>
                    <button class="mt-3 btn btn-primary">支払い</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    /* 基本設定*/
    const stripe_public_key = "{{ config('stripe.stripe_public_key') }}"
    const stripe = Stripe(stripe_public_key);
    const elements = stripe.elements();

    var cardNumber = elements.create('cardNumber');
    cardNumber.mount('#card-number');
    cardNumber.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var cardExpiry = elements.create('cardExpiry');
    cardExpiry.mount('#card-expiry');
    cardExpiry.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var cardCvc = elements.create('cardCvc');
    cardCvc.mount('#card-cvc');
    cardCvc.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('card-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var errorElement = document.getElementById('card-errors');
        if (event.error) {
            errorElement.textContent = event.error.message;
        } else {
            errorElement.textContent = '';
        }

        stripe.createToken(cardNumber).then(function(result) {
            if (result.error) {
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('card-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        form.submit();
    }
</script>
@endsection
