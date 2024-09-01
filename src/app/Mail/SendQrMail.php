<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQrMail extends Mailable
{
    use Queueable, SerializesModels;
    public $reservation_id;
    public $user_name;
    public $shop_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reservation_id, $user_name, $shop_name)
    {
        $this->reservation_id = $reservation_id;
        $this->user_name = $user_name;
        $this->shop_name = $shop_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.qr')
            ->subject('受付用ＱＲコードの送信')
            ->with([
                'url' => route('qrSend', ['reservation_id' => $this->reservation_id]),
                'content' => $this->reservation_id,
                'name' => $this->user_name,
                'shop' => $this->shop_name,
            ]);
    }
}
