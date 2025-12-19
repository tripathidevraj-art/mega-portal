<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Test command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Test the expired content check manually
Artisan::command('test:expired-check', function () {
    $this->call('content:check-expired');
})->purpose('Test the expired content check manually');

// Test logs cleanup manually
Artisan::command('test:clean-logs', function () {
    $this->call('logs:clean', ['--days' => 1]);
})->purpose('Test cleaning logs older than 1 day');