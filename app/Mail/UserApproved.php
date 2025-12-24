<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;

    public function __construct($user,$reason=null)
    {
    $this->user = $user;
    $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('🎉 Your Account Has Been Approved!')
                    ->view('emails.user-approved');
    }
}