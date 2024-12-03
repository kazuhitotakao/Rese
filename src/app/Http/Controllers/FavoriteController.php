<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function favorite(Request $request)
    {
        $shop_id = $request->shop_id;
        $user_favorite = Favorite::where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        $user = User::find(Auth::id());
        if ($user_favorite !== null) {
            $user->shops()->detach($shop_id);
        } else{
            $user->shops()->syncWithoutDetaching($shop_id);
        }

        $shops_id = session('search_results')->pluck('id');
        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shops_id = $shops_id->intersect($user_favorite_shop_id);
        return response()->json([
            'common_shops_id' => $common_shops_id
        ]);
    }
}
