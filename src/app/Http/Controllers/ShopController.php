<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Number;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $shops = Shop::with('genre')->get();
        $search = [
            'pref' => null,
            'genre_id' => null,
            'keyword' => null
        ];
        $genres = Genre::all();

        session(['search_results' => $shops]);
        session(['search' => $search]);

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

        $shops_id = session('search_results')->pluck('id');
        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shops_id = $shops_id->intersect($user_favorite_shop_id);
        return view('index', compact('shops', 'search', 'imagesUrl', 'genres', 'common_shops_id'));
    }

    public function search(Request $request)
    {
        if ($request->has('reset')) {
            $request->session()->forget(['search_results', 'search']);
            return redirect('/')->withInput();
        }


        $query = Shop::query();
        $query = $this->getSearchQuery($request, $query);
        $shops = $query->get();
        session(['search_results' => $shops]);
        $search = [
            'pref' => $request->pref,
            'genre_id' => $request->genre_id,
            'keyword' => $request->keyword
        ];
        session(['search' => $search]);
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

        $shops_id = session('search_results')->pluck('id');
        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shops_id = $shops_id->intersect($user_favorite_shop_id);
        return view('index', compact('shops', 'search', 'genres', 'imagesUrl', 'common_shops_id'));
    }

    private function getSearchQuery($request, $query)
    {
        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if (!empty($request->pref)) {
            $query->where('area', '=', $request->pref);
        }

        if (!empty($request->genre_id)) {
            $query->where('genre_id', '=', $request->genre_id);
        }

        return $query;
    }

    public function detail(Request $request)
    {
        $shop_id = $request->shop_id;
        $shops_id = session('search_results');
        if ($shops_id !== null) {
            $shops_id = $shops_id->pluck('id');
        } else {
            $shops_id = Shop::all()->pluck('id');
        }
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

        if (strpos($shop->image, 'http') === 0) {
            // 公開URLの場合
            $imagesUrl = $shop->image;
        } else {
            // ストレージ内の画像の場合
            $imagesUrl = Storage::url($shop->image);
        }

        return view('detail', compact('shops_id', 'shop_id', 'shop', 'times', 'numbers', 'time_id', 'number_id', 'date', 'comment', 'data_flg', 'imagesUrl'));
    }

    public function myPage(Request $request)
    {
        $user = User::find(Auth::id());
        $shops = Shop::with('genre')->get();

        $reservations = Reservation::where('user_id', Auth::id())->orderBy('date')->orderBy('time_id')->get();
        $shops_name = [];
        foreach ($reservations as $reservation) {
            $shopName = Shop::where('id', $reservation->shop_id)->pluck('name');
            $shops_name[] = $shopName->first();
        }

        $times = [];
        foreach ($reservations as $reservation) {
            $time = Time::where('id', $reservation->time_id)->pluck('time');
            $times[] = $time->first();
        }

        $times_id = [];
        foreach ($reservations as $reservation) {
            $time = Time::where('id', $reservation->time_id)->pluck('id');
            $times_id[] = $time->first();
        }

        $numbers = [];
        foreach ($reservations as $reservation) {
            $number = Number::where('id', $reservation->number_id)->pluck('number');
            $numbers[] = $number->first();
        }

        $numbers_id = [];
        foreach ($reservations as $reservation) {
            $number = Number::where('id', $reservation->number_id)->pluck('id');
            $numbers_id[] = $number->first();
        }

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

        $user_favorite_shops_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $times_all = Time::all();
        $numbers_all = Number::all();

        return view('my-page', compact('user', 'shops', 'reservations', 'shops_name', 'times', 'times_id', 'numbers', 'numbers_id', 'times_all', 'numbers_all', 'user_favorite_shops_id', 'imagesUrl'));
    }
}
