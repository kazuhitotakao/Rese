<?php

namespace App\Http\Controllers;

use App\Models\Max;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $shop = Shop::where('user_id', Auth::id())->first();
        $time10 = Max::where('user_id', Auth::id())->first()->pluck('time10');
        $time11 = Max::where('user_id', Auth::id())->first()->pluck('time11');
        $time12 = Max::where('user_id', Auth::id())->first()->pluck('time12');
        $time13 = Max::where('user_id', Auth::id())->first()->pluck('time13');
        $time14 = Max::where('user_id', Auth::id())->first()->pluck('time14');
        $time15 = Max::where('user_id', Auth::id())->first()->pluck('time15');
        $time16 = Max::where('user_id', Auth::id())->first()->pluck('time16');
        $time17 = Max::where('user_id', Auth::id())->first()->pluck('time17');
        $time18 = Max::where('user_id', Auth::id())->first()->pluck('time18');
        $time19 = Max::where('user_id', Auth::id())->first()->pluck('time19');
        $time20 = Max::where('user_id', Auth::id())->first()->pluck('time20');
        $time21 = Max::where('user_id', Auth::id())->first()->pluck('time21');
        $time22 = Max::where('user_id', Auth::id())->first()->pluck('time22');
        $time23 = Max::where('user_id', Auth::id())->first()->pluck('time23');
        return view('setting', compact(
            'shop',
            'time10',
            'time11',
            'time12',
            'time13',
            'time14',
            'time15',
            'time16',
            'time17',
            'time18',
            'time19',
            'time20',
            'time21',
            'time22',
            'time23'
        ));
    }

    public function save(Request $request)
    {
        $max = [
            'user_id' => Auth::id(),
            'time10' => $request->time10,
            'time11' => $request->time11,
            'time12' => $request->time12,
            'time13' => $request->time13,
            'time14' => $request->time14,
            'time15' => $request->time15,
            'time16' => $request->time16,
            'time17' => $request->time17,
            'time18' => $request->time18,
            'time19' => $request->time19,
            'time20' => $request->time20,
            'time21' => $request->time21,
            'time22' => $request->time22,
            'time23' => $request->time23,
        ];
        Max::where('user_id', Auth::id())->update($max);
        return redirect('/owner-page')->with('message', '設定を保存しました。');
    }
}
