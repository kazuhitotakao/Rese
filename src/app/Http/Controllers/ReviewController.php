<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public function store(Request $request, $shop_id)
    {
        dd($request->file('images'));
        // $images = [];
        // foreach ($request->file('images') as $image) {
        //     $images[] = $image;
        //     };
        // dd($images);

        return view('done_review');
    }
}
