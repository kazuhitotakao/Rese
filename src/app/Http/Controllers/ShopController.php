<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Number;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
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

    public function guest(Request $request)
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
        return view('guest', compact('shops', 'search', 'imagesUrl', 'genres', 'common_shops_id'));
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

        if ($shop) {
            $shop_interval = Shop::with('genre')->where('id', $shop_id)->first()->interval;
            $times = $this->generateTimeOptions($shop_interval);
        } else {
            $shop_interval = 15; // デフォルトで15分間隔
            $times = $this->generateTimeOptions($shop_interval);
        }

        $numbers = Number::all();

        if ($user_reservation !== null) {
            // 予約日と予約時間を組み合わせる
            $reservation_date_time = Carbon::parse($user_reservation->date->format('Y-m-d') . ' ' . $user_reservation->time);
            // 現在の日時を取得
            $now = Carbon::now();
        }
        if ($user_reservation == null) {
            $data_flg = false;
            $date = Carbon::today();
            $select_time = reset($times);
            $number_id = Number::first()->id;
            $comment = null;
            JavaScriptFacade::put([
                'flgBtn' => false,
            ]);
        } elseif ($reservation_date_time->gt($now)) {
            $data_flg = true;
            $date = $user_reservation->date;
            $select_time = $user_reservation->time;
            $number_id = $user_reservation->number_id;
            $comment = '※予約済※';
            JavaScriptFacade::put([
                'flgBtn' => true,
            ]);
        } else {
            $data_flg = false;
            $date = Carbon::today();
            $select_time = reset($times);
            $number_id = Number::first()->id;
            $comment = null;
            JavaScriptFacade::put([
                'flgBtn' => false,
            ]);
        }

        if (strpos($shop->image, 'http') === 0) {
            // 公開URLの場合
            $imagesUrl = $shop->image;
        } else {
            // ストレージ内の画像の場合
            $imagesUrl = Storage::url($shop->image);
        }

        // reviewを投稿済みかどうか（viewのレイアウト変更のフラグを立てる）
        $review = Review::where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        if ($review) {
            $review_flg = true;
            $review_rating = $review->review;
            $review_comment = $review->comment;
        } else {
            $review_flg = false;
            $review_rating = null;
            $review_comment = null;
        }

        return view('detail', compact('shops_id', 'shop_id', 'shop', 'times', 'numbers', 'select_time', 'number_id', 'date', 'comment', 'data_flg', 'imagesUrl', 'review_flg', 'review_rating', 'review_comment'));
    }

    private function generateTimeOptions($interval)
    {
        $times = [];
        $start = strtotime('10:00');
        $end = strtotime('23:59');

        for ($time = $start; $time <= $end; $time += $interval * 60) {
            $times[] = date('H:i', $time);
        }

        return $times;
    }

    public function myPage(Request $request)
    {
        $user = User::find(Auth::id());
        $shops = Shop::with('genre')->get();

        $reservations = Reservation::where('user_id', Auth::id())
            ->whereDate('date', '>=', Date::today())
            ->orderBy('date')->orderBy('time')->get();
        $shops_name = [];
        foreach ($reservations as $reservation) {
            $shopName = Shop::where('id', $reservation->shop_id)->pluck('name');
            $shops_name[] = $shopName->first();
        }

        $select_times = [];
        foreach ($reservations as $reservation) {
            $time = $reservation->time;
            $select_times[] = $time;
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
        $numbers_all = Number::all();

        $shops_id = [];
        foreach ($reservations as $reservation) {
            $shop_id = $reservation->shop_id;
            $shops_id[] = $shop_id;
        }

        $shops_interval = [];
        $count = 0;
        foreach ($reservations as $reservation) {
            $shop_interval = Shop::with('genre')->where('id', $shops_id[$count])->first()->interval;
            $shops_interval[] = $shop_interval;
            $count++;
        }

        $times = [];
        $count = 0;
        foreach ($reservations as $reservation) {
            $time = $this->generateTimeOptions($shops_interval[$count]);
            $times[] = $time;
            $count++;
        }

        return view('my-page', compact('user', 'shops', 'reservations', 'shops_id', 'shops_name', 'select_times', 'numbers', 'numbers_id', 'times', 'numbers_all', 'user_favorite_shops_id', 'imagesUrl'));
    }
}
