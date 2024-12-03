<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Number;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Shop;
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

        $shops = Shop::with(['genre', 'reviews' => function ($query) {
            $query->whereNotNull('review');
        }])->get();

        // 各ショップについて平均評価を計算し、データベースを更新
        foreach ($shops as $shop) {
            $reviews = $shop->reviews;
            $review_count = $reviews->count();
            $total_review = $reviews->sum('review');
            $review_average = $review_count > 0 ? round($total_review / $review_count, 1) : null;
            // 平均評価をショップモデルに保存
            $shop->average_rating = $review_average;
            $shop->save(); // データベースに保存
        };

        $sort = request()->input('sort', 'id');

        $search = [
            'area_id' => null,
            'genre_id' => null,
            'keyword' => null
        ];
        $areas = Area::all();
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
        return view('index', compact('shops', 'search', 'sort', 'imagesUrl', 'genres', 'areas', 'common_shops_id'));
    }

    public function guest(Request $request)
    {
        $user = User::find(Auth::id());
        $shops = Shop::with('genre', 'area')->get();
        $search = [
            'area_id' => null,
            'genre_id' => null,
            'keyword' => null
        ];
        $areas = Area::all();
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
        return view('guest', compact('shops', 'search', 'imagesUrl', 'genres', 'areas', 'common_shops_id'));
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
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'keyword' => $request->keyword
        ];
        session(['search' => $search]);
        $areas = Area::all();
        $genres = Genre::all();
        $sort = $request->input('sort', 'id'); // ビューに渡すために再度取得

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
        return view('index', compact('shops', 'search', 'sort', 'genres', 'areas', 'imagesUrl', 'common_shops_id'));
    }

    private function getSearchQuery($request, $query)
    {
        if (!empty($request->keyword)) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if (!empty($request->area_id)) {
            $query->where('area_id', '=', $request->area_id);
        }

        if (!empty($request->genre_id)) {
            $query->where('genre_id', '=', $request->genre_id);
        }
        // ソート処理
        $sort = request()->input('sort', 'id');
        switch ($sort) {
            case 'random':
                $query->inRandomOrder();
                break;
            case 'high_rating':
                // 評価が高い順、null値は最後(null ならば、 1 を返し、nullでない場合は 0 を返す)
                $query->orderByRaw('ISNULL(average_rating) ASC, average_rating DESC, id ASC');
                break;
            case 'low_rating':
                // 評価が低い順、null値は最後
                $query->orderByRaw('ISNULL(average_rating) ASC, average_rating ASC, id ASC');
                break;
            default:
                $query->orderBy($sort);
                break;
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
        $shop = Shop::with('genre', 'area')->where('id', $shop_id)->first();
        $user = User::find(Auth::id());
        $user_id = User::find(Auth::id())->id;

        if ($shop) {
            $shop_interval = Shop::with('genre', 'area')->where('id', $shop_id)->first()->interval;
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
        $review = Review::with('reviewImages')->where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        if ($review) {
            $review_flg = true;
            $review_rating = $review->review;
            $review_comment = $review->comment;
        } else {
            $review_flg = false;
            $review_rating = null;
            $review_comment = null;
        }

        // 画像のパスとIDをまとめた配列を作成
        if ($review) {
            $review_images = $review->reviewImages()->get();
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
        } else {
            $review_image_data = collect([]); // 空のコレクションを返す
        }

        return view('detail', compact('shops_id', 'shop_id', 'shop', 'times', 'numbers', 'select_time', 'number_id', 'date', 'comment', 'data_flg', 'imagesUrl', 'review_flg', 'review_rating', 'review_comment', 'review_image_data'));
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
        $shops = Shop::with('genre', 'area')->get();

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
            $shop_interval = Shop::with('genre', 'area')->where('id', $shops_id[$count])->first()->interval;
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
