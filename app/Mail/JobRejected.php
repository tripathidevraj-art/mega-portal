<?php

namespace App\Mail;

use App\Models\JobPosting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $reason;

    public function __construct(JobPosting $job, $reason)
    {
        $this->job = $job;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Your Job Posting Has Been Rejected')
                    ->view('emails.job-rejected')
                    ->with([
                        'job' => $this->job,
                        'reason' => $this->reason,
                    ]);
    }
}