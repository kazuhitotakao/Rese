<?php

namespace App\Console\Commands;

use App\Mail\SendReviewMail;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ReviewMailSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '予約時間を過ぎたらアンケートメールを送信する';

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
        // 当日の予約でまだメールを送ってないものを抽出
        $reservations = Reservation::where('date', '=', Carbon::today())
            ->where('review_mail_sent', null)
            ->get();
        // time_idから時間を抽出
        $times = [];
        foreach ($reservations as $reservation) {
            $times[] = Time::find($reservation->time_id)->time;
        }
        // 当日の予約でまだメールを送ってないもの、かつ、予約時間を過ぎた予約のreview_mail_sentにfalseを立てる
        $count = 0;
        foreach ($reservations as $reservation) {
            if ($times[$count] < Carbon::now()) {
                $reservation->review_mail_sent = false;
                $reservation->save();
            }
            ++$count;
        }
        // 当日の予約でまだメールを送ってないもの、かつ、review_mail_sentがfalseのものを抽出
        $reservations_over = Reservation::where('review_mail_sent', false)->get();

        foreach ($reservations_over as $reservation_over) {
            $data = [
                'name' => User::find($reservation_over->user_id)->name,
                'email' => User::find($reservation_over->user_id)->email,
                'shop' => Shop::find($reservation_over->shop_id)->name,
                'reservation_id' => $reservation_over->id
            ];
            Mail::to($data['email'])->send(new SendReviewMail($data));
            // リマインダー送信済みとしてマーク
            $reservation_over->review_mail_sent = true;
            $reservation_over->save();
            $count++;
        }
    }
}
