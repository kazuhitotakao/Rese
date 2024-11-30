<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Ui\Presets\React;

class ReviewController extends Controller
{
    public function show($shop_id)
    {
        $collect_shop_id = collect($shop_id);
        $shop = Shop::with('genre')->where('id', $shop_id)->first();

        if (strpos($shop->image, 'http') === 0) {
            // 公開URLの場合
            $image_url = $shop->image;
        } else {
            // ストレージ内の画像の場合
            $image_url = Storage::url($shop->image);
        }

        // お気に入りボタン実装用
        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shop_id = $collect_shop_id->intersect($user_favorite_shop_id);
        return view('review', compact('shop_id', 'shop', 'image_url', 'common_shop_id'));
    }

    public function store(ReviewRequest $request, $shop_id)
    {
        // リクエストから★評価とコメントを取得して、reviewsテーブルにレコード作成
        $param = [
            "user_id" => Auth::id(),
            "shop_id" => $shop_id,
            "review" => $request->rating,
            "comment" => $request->comment,
        ];
        $review = Review::with('reviewImages')->where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        // 追加
        if (!$review) {
            Review::create($param);
            // オブジェクトの再取得
            $review = Review::with('reviewImages')->where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        }

        // リクエストからファイルを取得してストレージに保存
        $directory = 'public/images/reviews';
        $image_paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (app('env') == 'local') {
                    $path = $image->store($directory);
                } elseif (app('env') == 'production') {
                    $path = Storage::disk('s3')->putFile('users', $image); //S3バケットのusersフォルダに、$imageを保存
                    $path = Storage::disk('s3')->url($path); //直前に保存した画像のS3上で付与されたurlを取得 https://~
                } elseif (app('env') == 'testing') {
                    // テスト環境の処理を追加
                    $path = $image->store($directory, 'local');
                }
                $image_paths[] = $path;
            }

            // ストレージに保存したものをreview_imagesテーブルに保存
            $images_data = [];
            foreach ($image_paths as $path) {
                $images_data[] = new ReviewImage(['image_path' => $path]);
            }
            $review->reviewImages()->saveMany($images_data); // すべての画像データを一括でデータベースに保存
        }

        return redirect()->route('detail', ['shop_id' => $shop_id]);
    }

    public function destroyReview(Request $request, $shop_id)
    {
        $review = Review::with('reviewImages')->where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        $review->delete();
        return redirect()->route('detail', ['shop_id' => $shop_id]);
    }

    public function editReview($shop_id)
    {
        $collect_shop_id = collect($shop_id);
        $shop = Shop::with('genre')->where('id', $shop_id)->first();
        $review = Review::with('reviewImages')->where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        $review_images = $review->reviewImages()->get();
        $review_rating = $review->review;
        $review_comment = $review->comment;

        // お店の画像
        if (strpos($shop->image, 'http') === 0) {
            // 公開URLの場合
            $image_url = $shop->image;
        } else {
            // ストレージ内の画像の場合
            $image_url = Storage::url($shop->image);
        }

        // 画像のパスとIDをまとめた配列を作成
        $review_image_data = $review_images->map(function ($image) {
            $path = $image->image_path;
            if (strpos($path, 'http') !== 0) {
                $path = Storage::url($path); // パスがHTTPで始まらない場合、URLを生成
            }
            return [
                'url' => $path,
                'id'  => $image->id
            ];
        });

        // お気に入りボタン実装用
        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shop_id = $collect_shop_id->intersect($user_favorite_shop_id);
        return view('review-edit', compact('shop_id', 'shop', 'image_url', 'common_shop_id', 'review_rating', 'review_comment', 'review_image_data'));
    }

    public function updateReview(ReviewRequest $request, $shop_id)
    {
        // リクエストから★評価とコメントを取得して、reviewsテーブルにレコード作成
        $param = [
            "user_id" => Auth::id(),
            "shop_id" => $shop_id,
            "review" => $request->rating,
            "comment" => $request->comment,
        ];
        $review = Review::with('reviewImages')->where('user_id', Auth::id())->where('shop_id', $shop_id)->first();

        // 更新
        if ($review) {
            $review->update($param);
            // オブジェクトの再取得
            $review = Review::with('reviewImages')->where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        }

        // 既存の画像はリクエストには入らない。このため、新規のリクエストされた画像だけが追加。
        // リクエストからファイルを取得してストレージに保存
        $directory = 'public/images/reviews';
        $image_paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if (app('env') == 'local') {
                    $path = $image->store($directory);
                } elseif (app('env') == 'production') {
                    $path = Storage::disk('s3')->putFile('users', $image); //S3バケットのusersフォルダに、$imageを保存
                    $path = Storage::disk('s3')->url($path); //直前に保存した画像のS3上で付与されたurlを取得 https://~
                } elseif (app('env') == 'testing') {
                    // テスト環境の処理を追加
                    $path = $image->store($directory, 'local');
                }
                $image_paths[] = $path;
            }

            // ストレージに保存したものをreview_imagesテーブルに保存
            $images_data = [];
            foreach ($image_paths as $path) {
                $images_data[] = new ReviewImage(['image_path' => $path]);
            }
            $review->reviewImages()->saveMany($images_data); // すべての画像データを一括でデータベースに保存
        }

        return redirect()->route('detail', ['shop_id' => $shop_id]);
    }

    public function destroyImage(Request $request)
    {
        $image_id = $request->image_id; // 画像IDをリクエストから取得
        Log::info('Destroying image with ID:', ['image_id' => $image_id]); // ログ記録

        $review_image = ReviewImage::find($image_id);
        if ($review_image) {
            // ファイルシステムから画像を削除する
            Storage::delete($review_image->image_path);

            // データベースから画像情報を削除
            $review_image->delete();

            // 成功時のレスポンスを返す
            return response()->json(['success' => true]);
        }
    }
}
