@extends('layouts.app')

@section('css')
    @if (app('env') == 'local')
        <link href="{{ asset('css/review.css') }}" rel="stylesheet">
    @endif
    @if (app('env') == 'production')
        <link href="{{ secure_asset('css/review.css') }}" rel="stylesheet">
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/review.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="shop-card__wrapper col-md-5 mt-5">
                <h3 class="shop-card__title">
                    今回のご利用はいかがでしたか？
                </h3>
                <div class="shop-card">
                    <div class="shop-card__image">
                        @if (empty($shop->image))
                            @if (app('env') == 'local')
                                <img class="shop-card__image-img" src="{{ asset('images/NoImage.png') }}" alt="image">
                            @endif
                            @if (app('env') == 'production')
                                <img class="shop-card__image-img" src="{{ secure_asset('images/NoImage.png') }}"
                                    alt="image">
                            @endif
                        @else
                            <img class="shop-card__image-img" src="{{ $image_url }}" alt="image">
                        @endif
                    </div>
                    <div class="shop-card__content">
                        <div class="shop-card__name">{{ $shop->name }}</div>
                        <div class="shop-card__tags">
                            <div class="shop-card__tag shop-card__tag--area">#{{ $shop->area }}</div>
                            <div class="shop-card__tag shop-card__tag--genre">#{{ $shop->genre->name }}</div>
                            <div class="shop-card__actions">
                                <form class="shop-card__form" action="{{ route('detail', ['shop_id' => $shop->id]) }}"
                                    method="get">
                                    <button class="shop-card__button btn">詳しく見る</button>
                                </form>
                                @if ($common_shop_id->isNotEmpty())
                                    @if ($shop_id === $common_shop_id[0])
                                        <i class="las la-heart like-button liked" data-id="{{ $shop->id }}"></i>
                                    @else
                                        <i class="lar la-heart like-button" data-id="{{ $shop->id }}"></i>
                                    @endif
                                @else
                                    <i class="lar la-heart like-button" data-id="{{ $shop->id }}"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 縦線の追加 -->
            <div class="col-md-1 mt-5 d-flex justify-content-center align-items-center">
                <div class="vertical-line"></div>
            </div>
            <div class="review col-md-6 mt-5">
                <form class="review__form" id="upload_form" action="{{ route('reviews.store', ['shop_id' => $shop_id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="review__form-group">
                        <div class="review__form-title">
                            <h4 class="review__form-label">体験を評価してください</h4>
                        </div>
                        <div class="review__form-content">
                            <div class="review__rating-options">
                                <input class="review__rating-input visually-hidden" id="rating-5" name="rating"
                                    type="radio" value="5" @if (old('rating') == '5') checked @endif>
                                <label class="review__rating-label" for="rating-5">★</label>
                                <input class="review__rating-input visually-hidden" id="rating-4" name="rating"
                                    type="radio" value="4" @if (old('rating') == '4') checked @endif>
                                <label class="review__rating-label" for="rating-4">★</label>
                                <input class="review__rating-input visually-hidden" id="rating-3" name="rating"
                                    type="radio" value="3" @if (old('rating') == '3') checked @endif>
                                <label class="review__rating-label" for="rating-3">★</label>
                                <input class="review__rating-input visually-hidden" id="rating-2" name="rating"
                                    type="radio" value="2" @if (old('rating') == '2') checked @endif>
                                <label class="review__rating-label" for="rating-2">★</label>
                                <input class="review__rating-input visually-hidden" id="rating-1" name="rating"
                                    type="radio" value="1" @if (old('rating') == '1') checked @endif>
                                <label class="review__rating-label" for="rating-1">★</label>
                            </div>
                        </div>
                        @error('rating')
                            <div class="review__form-error">
                                ※{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="review__form-group">
                        <div class="review__form-title">
                            <h4 class="review__form-label">口コミを投稿</h4>
                        </div>
                        <div class="review__form-content">
                            <div class="review__textarea-wrapper">
                                <textarea class="review__textarea" id="comment" name="comment" maxlength="400" placeholder="カジュアルな夜のお出かけにおすすめのスポット">{{ old('comment') }}</textarea>
                                <div class="review__textarea-char-counter">
                                    <span id="char_count">0</span>/400（最高文字数）
                                </div>
                            </div>
                        </div>
                        @error('comment')
                            <div class="review__form-error">
                                ※{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="review__form-group">
                        <div class="review__form-title">
                            <h4 class="review__form-label">画像の追加</h4>
                        </div>
                        <div class="review__form-content">
                            <div class="image-upload__container">
                                <label class="image-upload__dropzone" id="dropzone" for="image_upload">
                                    <p class="image-upload__text-click">クリックして写真を追加</p>
                                    <p class="image-upload__text-drop">またはドラッグアンドドロップ</p>
                                    <input class="image-upload__input" id="image_upload" name="images[]" type="file"
                                        accept="image/*" multiple>
                                </label>
                                @error('images.*')
                                    <div class="review__form-error">
                                        ※{{ $message }}
                                    </div>
                                @enderror
                                <div class="image-upload__preview" id="preview"></div>
                            </div>
                        </div>
                    </div>
                    <div class="review__submit-button-wrapper">
                        <button class="review__submit-button" type="submit">口コミを投稿</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
