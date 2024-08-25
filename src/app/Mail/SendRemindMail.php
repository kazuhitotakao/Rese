<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRemindMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.remind-to-user')
            ->subject('ご予約日当日のご案内')
            ->with([
                'reservation_id' => $this->data['reservation_id'],
                'name' => $this->data['name'],
                'shop' => $this->data['shop'],
                'date' => $this->data['date'],
                'time' => $this->data['time'],
                'number' => $this->data['number'],
            ]);
    }
}
