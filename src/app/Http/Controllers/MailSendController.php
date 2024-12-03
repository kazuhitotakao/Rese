<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailSendRequest;
use App\Mail\SendAdminMail;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendShopMail;

class MailSendController extends Controller
{
    public function shopToUser(MailSendRequest $request)
    {
        $reservation_id = $request->reservation_id;
        $user_id = Reservation::find($reservation_id)->user_id;
        $user = User::find($user_id);
        $user_name = User::find($user_id)->name;

        Mail::to($user)->send(new SendShopMail($request->subject, $request->content));
        return redirect('/owner-page')->with('messageEmail', $user_name . 'さんにメールを送信しました。');
    }

    public function adminToEach(MailSendRequest $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $user_name = User::find($user_id)->name;

        Mail::to($user)->send(new SendAdminMail($request->subject, $request->content));
        return redirect('/admin-page')->with('message', $user_name . 'さんにメールを送信しました。');
    }
}
