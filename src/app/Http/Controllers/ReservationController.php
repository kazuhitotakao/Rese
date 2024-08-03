<?php

namespace App\Http\Controllers;

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

class ReservationController extends Controller
{
    public function detail(Request $request)
    {
        $shop_id = $request->shop_id;
        $shops_id = session('search_results')->pluck('id');
        $user_reservation = Reservation::where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        $shop = Shop::with('genre')->where('id', $shop_id)->first();
        $user = User::find(Auth::id());
        $user_id = User::find(Auth::id())->id;

        $times = Time::all();
        $numbers = Number::all();

        if ($user_reservation == null) {
            $data_flg = false;
            $date = Carbon::today();
            $time_id = Time::first()->id;
            $number_id = Number::first()->id;
        } else {
            $data_flg = true;
            $date = $user_reservation->date;
            $time_id = $user_reservation->time_id;
            $number_id = $user_reservation->number_id;
        }

        if ($user_reservation == null) {
            $comment = null;
        } else {
            $comment = '※予約済※';
        }


        return view('detail', compact('shops_id', 'shop_id', 'shop', 'times', 'numbers', 'time_id', 'number_id', 'date', 'comment', 'data_flg'));
    }

    public function reserve(Request $request)
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

    public function doneBack(Request $request)
    {
        $shops = session('search_results');
        $search = session('search');
        $genres = Genre::all();

        $phpArray = json_decode($request->shops_id, true);
        $shops_id = collect($phpArray);

        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shops_id = $shops_id->intersect($user_favorite_shop_id);
        return view('index', compact('shops', 'search', 'genres', 'common_shops_id'));
    }

    public function cancel(Request $request)
    {
        $reservation_id = $request->reservation_id;
        Reservation::find($reservation_id)->delete();
        return redirect('/my-page');
    }
}
