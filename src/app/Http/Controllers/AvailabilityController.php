<?php

namespace App\Http\Controllers;

use App\Models\Max;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $shop_id = $request->shop_id;
        $shop = Shop::where('id', $shop_id)->first();
        $mark10 = '-';
        $mark11 = '-';
        $mark12 = '-';
        $mark13 = '-';
        $mark14 = '-';
        $mark15 = '-';
        $mark16 = '-';
        $mark17 = '-';
        $mark18 = '-';
        $mark19 = '-';
        $mark20 = '-';
        $mark21 = '-';
        $mark22 = '-';
        $mark23 = '-';
        $users = [];
        return view('availability', compact(
            'shop_id',
            'shop',
            'mark10',
            'mark11',
            'mark12',
            'mark13',
            'mark14',
            'mark15',
            'mark16',
            'mark17',
            'mark18',
            'mark19',
            'mark20',
            'mark21',
            'mark22',
            'mark23'
        ));
    }

    public function search(Request $request)
    {
        $shop_id = $request->shop_id;
        $shop = Shop::where('id', $shop_id)->first();
        if($shop->user_id == null){
            return redirect()->back()->with('message', '予約の空き状況について非対応のお店です');
        };
        // 検索したい日付の予約を抽出
        $reservation_search = Reservation::where('shop_id', $shop->id)->where('date', $request->date);
        $reservation_time10 = $reservation_search->where('time', 'LIKE', 10 . '%')->count();
        $reservation_time11 = $reservation_search->where('time', 'LIKE', 11 . '%')->count();
        $reservation_time12 = $reservation_search->where('time', 'LIKE', 12 . '%')->count();
        $reservation_time13 = $reservation_search->where('time', 'LIKE', 13 . '%')->count();
        $reservation_time14 = $reservation_search->where('time', 'LIKE', 14 . '%')->count();
        $reservation_time15 = $reservation_search->where('time', 'LIKE', 15 . '%')->count();
        $reservation_time16 = $reservation_search->where('time', 'LIKE', 16 . '%')->count();
        $reservation_time17 = $reservation_search->where('time', 'LIKE', 17 . '%')->count();
        $reservation_time18 = $reservation_search->where('time', 'LIKE', 18 . '%')->count();
        $reservation_time19 = $reservation_search->where('time', 'LIKE', 19 . '%')->count();
        $reservation_time20 = $reservation_search->where('time', 'LIKE', 20 . '%')->count();
        $reservation_time21 = $reservation_search->where('time', 'LIKE', 21 . '%')->count();
        $reservation_time22 = $reservation_search->where('time', 'LIKE', 22 . '%')->count();
        $reservation_time23 = $reservation_search->where('time', 'LIKE', 23 . '%')->count();
        // 予約枠を抽出
        $owner_id = $shop->user_id;
        $time10 = Max::where('user_id', $owner_id)->first()->pluck('time10');
        $time11 = Max::where('user_id', $owner_id)->first()->pluck('time11');
        $time12 = Max::where('user_id', $owner_id)->first()->pluck('time12');
        $time13 = Max::where('user_id', $owner_id)->first()->pluck('time13');
        $time14 = Max::where('user_id', $owner_id)->first()->pluck('time14');
        $time15 = Max::where('user_id', $owner_id)->first()->pluck('time15');
        $time16 = Max::where('user_id', $owner_id)->first()->pluck('time16');
        $time17 = Max::where('user_id', $owner_id)->first()->pluck('time17');
        $time18 = Max::where('user_id', $owner_id)->first()->pluck('time18');
        $time19 = Max::where('user_id', $owner_id)->first()->pluck('time19');
        $time20 = Max::where('user_id', $owner_id)->first()->pluck('time20');
        $time21 = Max::where('user_id', $owner_id)->first()->pluck('time21');
        $time22 = Max::where('user_id', $owner_id)->first()->pluck('time22');
        $time23 = Max::where('user_id', $owner_id)->first()->pluck('time23');
        if (($time10[0] - $reservation_time10) / $time10[0] > 0.5) {
            $mark10 = '〇';
        } elseif (($time10[0] - $reservation_time10) / $time10[0] > 0) {
            $mark10 = '△';
        } else {
            $mark10 = '✕';
        }
        if (($time11[0] - $reservation_time11) / $time11[0] > 0.5) {
            $mark11 = '〇';
        } elseif (($time11[0] - $reservation_time11) / $time11[0] > 0) {
            $mark11 = '△';
        } else {
            $mark11 = '✕';
        }

        if (($time12[0] - $reservation_time12) / $time12[0] > 0.5) {
            $mark12 = '〇';
        } elseif (($time12[0] - $reservation_time12) / $time12[0] > 0) {
            $mark12 = '△';
        } else {
            $mark12 = '✕';
        }

        if (($time13[0] - $reservation_time13) / $time13[0] > 0.5) {
            $mark13 = '〇';
        } elseif (($time13[0] - $reservation_time13) / $time13[0] > 0) {
            $mark13 = '△';
        } else {
            $mark13 = '✕';
        }

        if (($time14[0] - $reservation_time14) / $time14[0] > 0.5) {
            $mark14 = '〇';
        } elseif (($time14[0] - $reservation_time14) / $time14[0] > 0) {
            $mark14 = '△';
        } else {
            $mark14 = '✕';
        }

        if (($time15[0] - $reservation_time15) / $time15[0] > 0.5) {
            $mark15 = '〇';
        } elseif (($time15[0] - $reservation_time15) / $time15[0] > 0) {
            $mark15 = '△';
        } else {
            $mark15 = '✕';
        }

        if (($time16[0] - $reservation_time16) / $time16[0] > 0.5) {
            $mark16 = '〇';
        } elseif (($time16[0] - $reservation_time16) / $time16[0] > 0) {
            $mark16 = '△';
        } else {
            $mark16 = '✕';
        }

        if (($time17[0] - $reservation_time17) / $time17[0] > 0.5) {
            $mark17 = '〇';
        } elseif (($time17[0] - $reservation_time17) / $time17[0] > 0) {
            $mark17 = '△';
        } else {
            $mark17 = '✕';
        }

        if (($time18[0] - $reservation_time18) / $time18[0] > 0.5) {
            $mark18 = '〇';
        } elseif (($time18[0] - $reservation_time18) / $time18[0] > 0) {
            $mark18 = '△';
        } else {
            $mark18 = '✕';
        }

        if (($time19[0] - $reservation_time19) / $time19[0] > 0.5) {
            $mark19 = '〇';
        } elseif (($time19[0] - $reservation_time19) / $time19[0] > 0) {
            $mark19 = '△';
        } else {
            $mark19 = '✕';
        }

        if (($time20[0] - $reservation_time20) / $time20[0] > 0.5) {
            $mark20 = '〇';
        } elseif (($time20[0] - $reservation_time20) / $time20[0] > 0) {
            $mark20 = '△';
        } else {
            $mark20 = '✕';
        }

        if (($time21[0] - $reservation_time21) / $time21[0] > 0.5) {
            $mark21 = '〇';
        } elseif (($time21[0] - $reservation_time21) / $time21[0] > 0) {
            $mark21 = '△';
        } else {
            $mark21 = '✕';
        }

        if (($time22[0] - $reservation_time22) / $time22[0] > 0.5) {
            $mark22 = '〇';
        } elseif (($time22[0] - $reservation_time22) / $time22[0] > 0) {
            $mark22 = '△';
        } else {
            $mark22 = '✕';
        }

        if (($time23[0] - $reservation_time23) / $time23[0] > 0.5) {
            $mark23 = '〇';
        } elseif (($time23[0] - $reservation_time23) / $time23[0] > 0) {
            $mark23 = '△';
        } else {
            $mark23 = '✕';
        }
        return view('availability', compact(
            'shop_id',
            'shop',
            'reservation_time10',
            'reservation_time11',
            'reservation_time12',
            'reservation_time13',
            'reservation_time14',
            'reservation_time15',
            'reservation_time16',
            'reservation_time17',
            'reservation_time18',
            'reservation_time19',
            'reservation_time20',
            'reservation_time21',
            'reservation_time22',
            'reservation_time23',
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
            'time23',
            'mark10',
            'mark11',
            'mark12',
            'mark13',
            'mark14',
            'mark15',
            'mark16',
            'mark17',
            'mark18',
            'mark19',
            'mark20',
            'mark21',
            'mark22',
            'mark23'
        ));
    }
}
