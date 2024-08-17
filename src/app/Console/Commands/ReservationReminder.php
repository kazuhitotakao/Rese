<?php

namespace App\Console\Commands;

use App\Mail\SendRemindMail;
use App\Models\Number;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

class ReservationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '予約当日の朝に予約情報のリマインダーを送る';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 当日の予約
        $reservation_today = Reservation::where('date', '=', Carbon::today());
        // 当日に予約をしているユーザー
        $reservation_users_id = $reservation_today->pluck('user_id');
        $users = [];
        foreach ($reservation_users_id as $reservation_user_id) {
            $users[] = User::find($reservation_user_id);
        }

        // 店名
        $reservation_shops_id = $reservation_today->pluck('shop_id');
        $shops = [];
        foreach ($reservation_shops_id as $reservation_shop_id) {
            $shops[] = Shop::find($reservation_shop_id)->name;
        }
        // 日付
        $date = Carbon::today()->format('Y-m-d');

        // 時間
        $reservation_times_id = $reservation_today->pluck('time_id');
        $times = [];
        foreach ($reservation_times_id as $reservation_time_id) {
            $times[] = Time::find($reservation_time_id)->time
                ->format('H:i');
        }

        // 人数
        $reservation_numbers_id = $reservation_today->pluck('number_id');
        $numbers = [];
        foreach ($reservation_numbers_id as $reservation_number_id) {
            $numbers[] = Number::find($reservation_number_id)->number;
        }

        // データを一つにまとめる
        $count = 0;
        foreach ($users as $user) {
            $data = [
                'name' => $user->name,
                'shop' => $shops[$count],
                'date' => $date,
                'time' => $times[$count],
                'number' => $numbers[$count],
            ];
            Mail::to($user->email)->send(new SendRemindMail($data));
            $count++;
        }
    }
}
