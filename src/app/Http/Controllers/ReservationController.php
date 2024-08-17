<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Favorite;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\Time;
use App\Models\Number;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReservationController extends Controller
{
    public function reserve(ReservationRequest $request)
    {
        $shops_id = $request->shops_id;
        $shop_id = $request->shop_id;
        $date = $request->date;
        $time_id = $request->time_id;
        $number_id = $request->number_id;
        $user = User::find(Auth::id());
        $user_reservation = Reservation::where('user_id', Auth::id())->where('shop_id', $shop_id)->first();

        if ($user_reservation == null) {
            $user->reservations()->attach($shop_id, ['date' => $date, 'time_id' => $time_id, 'number_id' => $number_id]);
        } else {
            $user->reservations()->updateExistingPivot($shop_id, ['date' => $date, 'time_id' => $time_id, 'number_id' => $number_id]);
        }

        return view('done', compact('shops_id'));
    }

    public function reserveChange(ReservationRequest $request)
    {
        $form = $request->all();
        unset($form['_token']);
        Reservation::find($request->reservation_id)->update($form);
        return redirect('/my-page');
    }

    public function doneBack(Request $request)
    {
        $shops = session('search_results');
        $search = session('search');
        $genres = Genre::all();

        $imagesUrl = [];
        foreach ($shops as $shop) {
            if (strpos($shop->image, 'http') === 0) {
                // 公開URLの場合
                $imagesUrl[] = $shop->image;
            } else {
                // ストレージ内の画像の場合
                $imagesUrl[] = Storage::url($shop->image);
            }
        }

        $phpArray = json_decode($request->shops_id, true);
        $shops_id = collect($phpArray);

        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shops_id = $shops_id->intersect($user_favorite_shop_id);
        return view('index', compact('shops', 'search', 'genres', 'imagesUrl', 'common_shops_id'));
    }

    public function cancel(Request $request)
    {
        $reservation_id = $request->reservation_id;
        Reservation::find($reservation_id)->delete();
        return redirect('/my-page');
    }
}
