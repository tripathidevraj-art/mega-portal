<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralInviteMail extends Mailable
{
    public $sender;
    public $recipientName;
    public $link;

    public function __construct($sender, $recipientName, $link)
    {
        $this->sender = $sender;
        $this->recipientName = $recipientName;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('You’ve been invited to JobPortal!')
                    ->view('emails.referral.invite');
    }
}
