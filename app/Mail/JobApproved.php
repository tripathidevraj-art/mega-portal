<?php

namespace App\Mail;

use App\Models\JobPosting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $reason;

    public function __construct(JobPosting $job, $reason = null)
    {
        $this->job = $job;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Your Job Posting Has Been Approved')
                    ->view('emails.job-approved')
                    ->with([
                        'job' => $this->job,
                        'reason' => $this->reason,
                    ]);
    }
}