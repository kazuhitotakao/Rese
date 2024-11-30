<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $shop_id = $request->shop_id;
        $shop = Shop::find($shop_id);

        $reviews = Review::with('user', 'reviewImages')->where('shop_id', $shop_id)->get();

        $review = Review::with('user', 'reviewImages')->where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        // $reviewがnullでないかチェック
        if ($review !== null) {
            $review_images = $review->reviewImages()->get();
        } else {
            // レビューが見つからない場合、$review_imagesを空のコレクションや適切なデフォルト値で初期化
            $review_images = collect();
        }

        // レビューとそれに関連する画像データを処理
        $review_data = $reviews->map(function ($review) {
            $review_images = $review->reviewImages;
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

            // レビューのユーザー情報と画像データを組み合わせる
            return [
                'review_id' => $review->id,
                'user_id' => $review->user->id,
                'user_name' => $review->user->name,
                'updated_at' => $review->updated_at,
                'review_rating' => $review->review,
                'review_comment' => $review->comment,
                'review_images' => $review_image_data,

            ];
        });

        return view('comment', compact('shop_id', 'shop', 'review_data'));
    }
    public function destroyComment(Request $request)
    {
        $review = Review::find($request->review_id);
        $review->delete();
        return redirect()->route('shop.comments.index', ['shop_id' => $request->shop_id]);
    }
}
