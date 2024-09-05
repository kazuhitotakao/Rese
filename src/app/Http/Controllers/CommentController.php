<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function view(Request $request)
    {
        $shop = Shop::where('user_id', Auth::id())->first();
        $reservations = Reservation::where('shop_id', $shop->id)
            ->whereNotNull('comment_at')->get();

        $users = [];
        foreach ($reservations as $reservation) {
            $user = User::where('id', $reservation->user_id)->pluck('name');
            $users[] = $user->first();
        }
        return view('comment', compact('shop', 'reservations', 'users'));
    }
}
