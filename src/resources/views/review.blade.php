@extends('layouts.app')

@section('css')
    @if (app('env') == 'local')
        <link href="{{ asset('css/review.css') }}" rel="stylesheet">
    @endif
    @if (app('env') == 'production')
        <link href="{{ secure_asset('css/review.css') }}" rel="stylesheet">
    @endif
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
                                <div class="image-upload__preview" id="preview"></div>
                            </div>
                        </div>
                    </div>
                    <button class="review__submit-button">口コミを投稿</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // 最大文字数
        const maxLength = 400;

        // 要素の取得
        const textarea = document.getElementById('comment');
        const charCount = document.getElementById('char_count');

        // イベントリスナーを設定
        textarea.addEventListener('input', () => {
            // 入力された文字数を取得
            const currentLength = textarea.value.length;

            // 文字数を表示
            charCount.textContent = currentLength;
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.like-button').on('click', function() {
                const shop_id = $(this).data('id');
                if (this.classList.contains('liked')) {
                    this.classList.remove('liked');
                    this.classList.replace('las', 'lar');
                } else {
                    this.classList.add('liked');
                    this.classList.replace('lar', 'las');
                }
                $.ajax({
                    url: '/favorite',
                    type: 'POST',
                    data: {
                        shop_id: shop_id
                    },
                    dataType: "json",
                }).done(function(res) {
                    console.log(res);
                }).fail(function() {
                    alert('通信の失敗をしました');
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const dropzone = document.getElementById('dropzone');
            const input = document.getElementById('image_upload');
            const preview = document.getElementById('preview');
            const uploadedFiles = []; // すべてのファイルを管理する配列

            // ドラッグイベント
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.style.backgroundColor = '#d3d3d3';
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.style.backgroundColor = '';
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.style.backgroundColor = '';
                const files = Array.from(e.dataTransfer.files);
                // 配列にファイルを追加
                addFiles(files);
                handleFiles(files);
            });

            // クリックでファイル選択
            input.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);

                // 配列にファイルを追加
                addFiles(files);
                handleFiles(files);
            });

            // 配列にファイルを追加（重複を避ける）
            function addFiles(files) {
                files.forEach((file) => {
                    if (!uploadedFiles.some((uploadedFile) => uploadedFile.name === file.name)) {
                        uploadedFiles.push(file);
                    }
                });
                // 配列内容を `<input>` に反映
                updateInputFiles();
            }

            // `<input>` の `files` を更新
            function updateInputFiles() {
                const dataTransfer = new DataTransfer();

                uploadedFiles.forEach((file) => {
                    dataTransfer.items.add(file);
                });

                input.files = dataTransfer.files; // `<input>` に反映
            }

            // ファイルを処理してプレビューに表示
            function handleFiles(files) {
                for (const file of files) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    </script>
@endsection
