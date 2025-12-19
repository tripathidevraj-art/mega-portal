<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSuspended extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reason;
    public $durationDays;

    public function __construct(User $user, $reason, $durationDays)
    {
        $this->user = $user;
        $this->reason = $reason;
        $this->durationDays = $durationDays;
    }

    public function build()
    {
        return $this->subject('Account Suspension Notice')
                    ->view('emails.user-suspended')
                    ->with([
                        'user' => $this->user,
                        'reason' => $this->reason,
                        'durationDays' => $this->durationDays,
                    ]);
    }
}