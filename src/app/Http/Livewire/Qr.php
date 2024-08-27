<?php

namespace App\Http\Livewire;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Qr extends Component
{
    public function render()
    {
        return view('livewire.qr');
    }

    public function check($id)
    {
        $reservation = Reservation::find($id);
        if (isset($reservation) && !empty($reservation)) {
            $check = [
                'check_in' => '来店',
                'check_in_at' => Carbon::now(),
            ];
            Reservation::where('id', $id)->update($check);
            session()->flash('message', 'ようこそいらっしゃいませ。受付完了しました。');
        } else {
            session()->flash('message', '予約が見つかりません。');
        }
    }
}
