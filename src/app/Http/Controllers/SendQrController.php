<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendQrController extends Controller
{
    public function show($reservation_id)
    {
        return view('qr-show', compact('reservation_id'));
    }
}
