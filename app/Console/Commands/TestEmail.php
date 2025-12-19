<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail as TestEmailMail;

class TestEmail extends Command
{
    protected $signature = 'email:test {email?}';
    protected $description = 'Test email configuration';

    public function handle()
    {
        $email = $this->argument('email') ?: 'test@example.com';
        
        try {
            $this->info("Sending test email to: {$email}");
            
            Mail::to($email)->send(new TestEmailMail());
            
            $this->info('Email sent successfully!');
            $this->info('Check your inbox (and spam folder).');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}