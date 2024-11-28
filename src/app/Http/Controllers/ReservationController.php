<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Mail\SendQrMail;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Max;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\Time;
use App\Models\Number;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ReservationController extends Controller
{
    public function reserve(ReservationRequest $request)
    {
        $shops_id = $request->shops_id;
        $shop_id = $request->shop_id;
        $shop = Shop::where('id', $shop_id)->first();
        $shop_name = $shop->name;
        $date = $request->date;
        $time = $request->time;
        $number_id = $request->number_id;
        $user = User::find(Auth::id());
        $user_reservation = Reservation::where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        $time_head = substr($time, 0, 2);

        // 直近の予約の日時オブジェクトを作成
        $reservation_date = Reservation::where('user_id', Auth::id())->where('shop_id', $shop_id)->latest('created_at')->first()->date;
        $reservation_time = Reservation::where('user_id', Auth::id())->where('shop_id', $shop_id)->latest('created_at')->first()->time;
        $reservation_date_time = Carbon::parse($reservation_date->format('Y-m-d') . ' ' . $reservation_time);
        // 現在の日時を取得
        $now = Carbon::now();

        $reservation_time = Reservation::where('user_id', Auth::id())->where('shop_id', $shop->id)->where('date', $date)->pluck('time');
        $reservation_already_count = Reservation::where('shop_id', $shop->id)->where('date', $date)
            ->where('time', 'LIKE', $time_head . '%')->count();
        $owner_id = $shop->user_id;
        $reservation_max = Max::where('user_id', $owner_id)->first();
        if ($reservation_max !== null) {
            $reservation_max = Max::where('user_id', $owner_id)->first()->pluck('time' . $time_head); //time10とかカラム名
            if ($reservation_max[0] - $reservation_already_count == 0) {
                if (substr($reservation_time[0], 0, 2) !== $time_head) {
                    return redirect()->back()->with('message', "$date $time_head 時台の予約はすでに満員です。");
                }
            }
        }
        if ($user_reservation == null) {
            $user->reservations()->attach($shop_id, ['date' => $date, 'time' => $time, 'number_id' => $number_id]);
            $reservation_id = Reservation::where('user_id', Auth::id())->where('shop_id', $shop_id)->latest('created_at')->first()->id;
            $user_id = Reservation::find($reservation_id)->user_id;
            $user = User::find($user_id);
            $user_name = User::find($user_id)->name;
            Mail::to($user)->send(new SendQrMail($reservation_id, $user_name, $shop_name));
        } elseif ($reservation_date_time->gt($now)) {  // 直近の予約日時が現在時刻より先の日時の場合
            $user->reservations()->updateExistingPivot($shop_id, ['date' => $date, 'time' => $time, 'number_id' => $number_id]);
        } else {
            $user->reservations()->attach($shop_id, ['date' => $date, 'time' => $time, 'number_id' => $number_id]);
            $reservation_id = Reservation::where('user_id', Auth::id())->where('shop_id', $shop_id)->latest('created_at')->first()->id;
            $user_id = Reservation::find($reservation_id)->user_id;
            $user = User::find($user_id);
            $user_name = User::find($user_id)->name;
            Mail::to($user)->send(new SendQrMail($reservation_id, $user_name, $shop_name));
        }

        return view('done', compact('shops_id'));
    }

    public function reserveChange(ReservationRequest $request)
    {
        $form = $request->all();
        $id = $request->reservation_id;
        $date = $request->date;
        $time = $request->time;
        $shop_id = Reservation::find($id)->shop_id;
        $shop = Shop::where('id', $shop_id)->first();
        $time_head = substr($time, 0, 2);

        $reservation_time = Reservation::where('user_id', Auth::id())->where('shop_id', $shop->id)->where('date', $date)->pluck('time');
        $reservation_already_count = Reservation::where('shop_id', $shop->id)->where('date', $date)
            ->where('time', 'LIKE', $time_head . '%')->count();


        $owner_id = $shop->user_id;
        $reservation_max = Max::where('user_id', $owner_id)->first();
        if ($reservation_max !== null) {
            $reservation_max = Max::where('user_id', $owner_id)->first()->pluck('time' . $time_head); //time10とかカラム名
            if ($reservation_max[0] - $reservation_already_count == 0) {
                if (substr($reservation_time[0], 0, 2) !== $time_head) {
                    return redirect()->back()->with('message', "$date $time_head 時台の予約はすでに満員です。");
                }
            }
        }
        unset($form['_token']);
        Reservation::find($id)->update($form);
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

    public function qr()
    {
        return view('qr-scan');
    }
}
