<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResisterShopRequest;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Number;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Time;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class RegisteredShopController extends Controller
{
    public function ownerPage(Request $request)
    {
        $user = User::find(Auth::id());
        $shop = Shop::where('user_id', Auth::id())->first();
        $image = Image::where('user_id', Auth::id())->first();
        $genres = Genre::all();

        if($image == null){
            JavaScriptFacade::put([
                'flgBtn' => false,
            ]);
        } else {
            JavaScriptFacade::put([
                'flgBtn' => true,
            ]);
        }
        // --shopデータが存在しない場合
        if ($shop == null) {
            $reservations = [];
            $users_name = [];
            $times = [];
            $numbers = [];
            return view('owner-page', compact('user', 'shop', 'image', 'genres', 'reservations', 'users_name', 'times', 'numbers'));
        }

        // --shopデータが存在する場合
        // URLが公開URLであるか、アプリのストレージ内での画像かを判別する
        if (strpos($shop->image, 'http') === 0) {
            // 公開URLの場合
            $imageUrl = $shop->image;
        } else {
            // ストレージ内の画像の場合
            $imageUrl = Storage::url($shop->image);
        }

        $reservations = Reservation::where('shop_id', $shop->id)
            ->where('date', '>=', today())
            ->orderBy('date')->orderBy('time_id')->get();

        $users_name = [];
        foreach ($reservations as $reservation) {
            $user_name = User::where('id', $reservation->user_id)->pluck('name');
            $users_name[] = $user_name->first();
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

        return view('owner-page', compact('user', 'shop', 'image', 'imageUrl', 'genres', 'reservations', 'users_name', 'times', 'numbers'));
    }

    public function saveOrUpdate(ResisterShopRequest $request)
    {
        $form = [
            'name' => $request->name,
            'area' => $request->area,
            'genre_id' => $request->genre_id,
            'overview' => $request->overview,
            'image' =>  null,
            'user_id' => Auth::id(),
        ];

        $image = Image::where('user_id', Auth::id())->first();
        if($image !== null){
            $form['image'] = $image->path;
        }
        
        if ($request->action === 'register') {
            $own_shop = Shop::where('user_id', Auth::id())->first();
            if ($own_shop == null) {
                Shop::create($form);
                return redirect('/owner-page')->with('message', '店舗情報を登録しました。');
            } else {
                return redirect('/owner-page')->with('message', '※登録がキャンセルされました。<br>※店舗代表者１人につき登録する店舗は１つでお願いします。');
            }
        } elseif ($request->action === 'update') {
            Shop::where('user_id', Auth::id())->update($form);
            return redirect('/owner-page')->with('message', '店舗情報を更新しました。');
        }
    }
}
