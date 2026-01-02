<?php

namespace App\Mail;

use App\Models\JobPosting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobRestoredNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $job;

    public function __construct(JobPosting $job)
    {
        $this->job = $job;
    }

    public function build()
    {
        return $this->subject('Your Job Posting Is Now Live Again')
                    ->view('emails.job-restored')
                    ->with(['job' => $this->job]);
    }
}