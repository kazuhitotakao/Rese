<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        $user_id = 2;
        $reservation = Reservation::where('user_id', $user_id)->first();
        $reservation_id = $reservation->id;
        return view('test', compact('reservation_id'));
    }
}
