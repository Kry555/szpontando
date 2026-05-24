<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangeOldMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

public function build()
{
    return $this->subject('Zmiana email - potwierdzenie')
        ->view('emails.change_email_old')
        ->with([
            'link' => $this->link
        ]);
}
}