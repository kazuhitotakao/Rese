<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $review = Review::where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        // 更新 or 追加 (2つ目の口コミ追加をview側でも防ぐが、念のために、口コミがすでにある場合、更新処理を実施)
        if ($review) {
            $review->update($param);
        } else {
            Review::create($param);
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
        }

        // 前処理でストレージに保存したものをreview_imagesテーブルに保存
        $images_data = [];
        foreach ($image_paths as $path) {
            $images_data[] = new ReviewImage(['image_path' => $path]);
        }
        $review->reviewImages()->saveMany($images_data); // すべての画像データを一括でデータベースに保存

        return redirect()->route('detail', ['shop_id' => $shop_id]);
    }
}
