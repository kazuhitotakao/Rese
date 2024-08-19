<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReviewMail extends Mailable
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
        return $this->view('emails.review-to-user')
        ->subject('アンケートのお願い')
        ->with([
            'name' => $this->data['name'],
            'shop' => $this->data['shop'],
            'url' => route('review', ['reservation_id' =>$this->data['reservation_id']]),
        ]);
    }
}
