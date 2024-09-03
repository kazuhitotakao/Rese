@extends('layouts.app')

@section('css')
@if(app('env')=='local')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endif
@if(app('env')=='production')
<link rel="stylesheet" href="{{ secure_asset('css/review.css') }}">
@endif
@endsection

@section('content')

<div class="mail__content">
    <div class="card">
        <h3 class="card__title">
            アンケートにご協力ください
        </h3>
        <form class="form" action="{{ url('/review/' . $reservation_id) }}" method="post">
            @csrf
            <div class="form__group">
                <div class="form__group-title">
                    <h4 class="form__label--item">・お店について</h4>
                </div>
                <div class="form__group-content--review">
                    <div class="form__input--radio">
                        <input class="visually-hidden" type="radio" name="review" value="5" id="5" @if ( old ('review')=='5' ) checked @endif>
                        <label class="form__input--radio-label" for="5">5　満足</label>
                        <input class="visually-hidden" type="radio" name="review" value="4" id="4" @if ( old ('review')=='4' ) checked @endif>
                        <label class="form__input--radio-label" for="4">4　やや満足</label>
                        <input class="visually-hidden" type="radio" name="review" value="3" id="3" @if ( old ('review')=='3' ) checked @endif>
                        <label class="form__input--radio-label" for="3">3　普通</label>
                        <input class="visually-hidden" type="radio" name="review" value="2" id="2" @if (old ('review')=='2' ) checked @endif>
                        <label class="form__input--radio-label" for="2">2　ややダメ</label>
                        <input class="visually-hidden" type="radio" name="review" value="1" id="1" @if (old ('review')=='1' ) checked @endif>
                        <label class="form__input--radio-label" for="1">1　ダメ</label>
                    </div>
                </div>
            </div>
            <div class="form__group">
                <div class="form__group-title">
                    <h4 class="form__label--item">・感想、コメント</h4>
                </div>
                <div class="form__group-content">
                    <div class="form__input--textarea">
                        <textarea name="comment" placeholder="気軽に感想等をお聞かせください。">{{ old('comment') }}</textarea>
                    </div>
                </div>
            </div>

            <button class="card__button-submit">回答する</button>
        </form>
    </div>
</div>

@endsection