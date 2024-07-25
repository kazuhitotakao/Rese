<?php

namespace App\Http\Controllers;

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
        $user_reservation = Reservation::where('user_id', Auth::id())->where('shop_id', $shop_id)->first();
        $shop = Shop::with('genre')->where('id', $shop_id)->first();
        $user = User::find(Auth::id());
        $user_id = User::find(Auth::id())->id;
        $date = Carbon::today();

        $times = Time::all();
        if ($user_reservation == null) {
            $time_id = Time::first()->id;
        } else {
            $time_id = $user_reservation->time_id;
        }

        $numbers = Number::all();
        if ($user_reservation == null) {
            $number_id = Number::first()->id;
        } else {
            $number_id = $user_reservation->number_id;
        }

        if ($user_reservation == null) {
            $user->reservations()->attach($shop_id, ['date' => $date, 'time_id' => $time_id, 'number_id' => $number_id]);
        }
        return view('detail', compact('shop', 'times', 'numbers', 'time_id', 'number_id', 'date'));
    }

    public function done(Request $request)
    {
        return view('done');
    }

    public function reserve(Request $request)
    {   
        $shop_id = $request->shop_id;
        $date = $request->date;
        $time_id = $request->time_id;
        $number_id = $request->number_id;
        $user = User::find(Auth::id());
        $user->reservations()->updateExistingPivot($shop_id, ['date' => $date, 'time_id' => $time_id, 'number_id' => $number_id]);
        return redirect('/done');
    }

}
