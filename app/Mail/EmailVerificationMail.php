<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable; // ← removed Queueable
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use SerializesModels; // ← keep this (for User model)

    public $user;
    public $verificationUrl;

    public function __construct(User $user, $verificationUrl)
    {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
    }

    public function build()
    {
        return $this->subject('Verify Your Email - Job & Offer Portal')
                    ->view('emails.verify-email');
    }
}