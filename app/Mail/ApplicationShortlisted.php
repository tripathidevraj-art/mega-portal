<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationShortlisted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Congratulations! You\'ve Been Shortlisted - ' . $this->application->job->job_title)
                    ->view('emails.application-shortlisted');
    }
}