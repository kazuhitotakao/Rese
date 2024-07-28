<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Number;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Time;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $shops_id = session('search_results')->pluck('id');
        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shops_id = $shops_id->intersect($user_favorite_shop_id);
        return view('index', compact('shops', 'search', 'genres', 'common_shops_id'));
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

        $shops_id = session('search_results')->pluck('id');
        $user_favorite_shop_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        $common_shops_id = $shops_id->intersect($user_favorite_shop_id);
        return view('index', compact('shops', 'search', 'genres', 'common_shops_id'));
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

        $numbers = [];
        foreach ($reservations as $reservation) {
            $number = Number::where('id', $reservation->number_id)->pluck('number');
            $numbers[] = $number->first();
        }


        $user_favorite_shops_id = Favorite::where('user_id', Auth::id())->orderBy('shop_id')->get()->pluck('shop_id');
        return view('my-page', compact('user', 'shops', 'reservations', 'shops_name', 'times', 'numbers', 'user_favorite_shops_id'));
    }
}
