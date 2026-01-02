<?php

namespace App\Mail;

use App\Models\JobPosting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobRemovedDueToReport extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $adminNotes;

    public function __construct(JobPosting $job, ?string $adminNotes)
    {
        $this->job = $job;
        $this->adminNotes = $adminNotes;
    }

    public function build()
    {
        return $this->subject('Your Job Posting Has Been Removed')
                    ->view('emails.job-removed-report')
                    ->with([
                        'job' => $this->job,
                        'adminNotes' => $this->adminNotes,
                    ]);
    }
}