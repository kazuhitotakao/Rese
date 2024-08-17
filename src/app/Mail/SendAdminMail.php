<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $content;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->text('emails.admin-to-each_text')
        ->view('emails.admin-to-each')
        ->subject($this->subject)
            ->with([
                'content' => $this->content,
            ]);
    }
}
