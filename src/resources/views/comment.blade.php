@extends('layouts.app')

@section('css')
    @if (app('env') == 'local')
        <link href="{{ asset('css/comment.css') }}" rel="stylesheet">
    @endif
    @if (app('env') == 'production')
        <link href="{{ secure_asset('css/comment.css') }}" rel="stylesheet">
    @endif
    <link href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css"
        rel="stylesheet">
@endsection

@section('content')
    <div class="comment">
        <div class="comment__header">
            <a class="comment__back-btn" href="{{ route('detail', ['shop_id' => $shop_id]) }}">&lt</a>
            <h2 class="comment__shop-name">{{ $shop->name }}</h2>
        </div>
        <div class="comment__body">
            <div class="comment__title">
                <h2 class="comment__title-text">全ての口コミ情報</h2>
            </div>
            <div class="comment__reviews">
                @foreach ($review_data as $index => $review)
                    <div class="comment__review">
                        @can('register')
                            <form action="{{ route('comments.destroy', ['shop_id' => $shop->id]) }}" method="post"
                                onsubmit="return confirm('このコメントを削除してもよろしいですか？');">
                                @csrf
                                @method('DELETE')
                                <input name="review_id" type="hidden" value="{{ $review['review_id'] }}">
                                <button class="comment__review-delete-button"><i class="lar la-times-circle"></i></button>
                            </form>
                        @endcan
                        @can('user')
                            @if (Auth::check() && Auth::id() === $review['user_id'])
                                <form action="{{ route('comments.destroy', ['shop_id' => $shop->id]) }}" method="post"
                                    onsubmit="return confirm('このコメントを削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <input name="review_id" type="hidden" value="{{ $review['review_id'] }}">
                                    <button class="comment__review-delete-button"><i class="lar la-times-circle"></i></button>
                                </form>
                            @endif
                        @endcan
                        <p class="comment__review-title">【口コミ{{ $index + 1 }}】</p>
                        <p class="comment__review-user">{{ $review['user_name'] }}</p>
                        <p class="comment__review-date">{{ $review['updated_at']->format('Y-m-d H:i') }}</p>
                        <div class="comment__review-rating">
                            <span class="star5_rating" data-rate="{{ $review['review_rating'] }}"></span>
                        </div>
                        <p class="comment__review-text">{{ $review['review_comment'] }}</p>
                        <div class="comment__review-images">
                            @foreach ($review['review_images'] as $image)
                                <form action="">
                                    <div class="comment__review-image-container">
                                        <img src="{{ $image['url'] }}" alt="Uploaded image">
                                    </div>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
