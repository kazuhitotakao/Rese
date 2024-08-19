<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function review($reservation_id)
    {
        return view('review',compact('reservation_id'));
    }
    
    public function reviewPost(Request $request, $reservation_id)
    {
        $form = $request->all();
        unset($form['_token']);
        $form['comment_at'] = Carbon::now();
        Reservation::find($reservation_id)->update($form);
        return view('done_review');
    }
}
