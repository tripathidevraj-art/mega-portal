<?php

namespace App\Mail;

use App\Models\JobReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewJobReportNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    public function __construct(JobReport $report)
    {
        $this->report = $report;
    }

    public function build()
    {
        return $this->subject('🚨 New Job Report Submitted')
                    ->view('emails.new-job-report')
                    ->with(['report' => $this->report]);
    }
}