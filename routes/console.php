<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule appointment reminders for the next day at 8 AM
Schedule::command('appointments:send-reminders --days=1')->dailyAt('08:00');

// Process SMS queue every 5 minutes during business hours
Schedule::command('sms:process --limit=50')->everyFiveMinutes()->between('06:00', '22:00');

// Process SMS queue with retries once daily at 9 AM
Schedule::command('sms:process --limit=100 --retry')->dailyAt('09:00');

// Send appointment reminders 3 days in advance at 4 PM (optional)
Schedule::command('appointments:send-reminders --days=3')->dailyAt('16:00');

// Clean up old processed SMS notifications older than 30 days
Schedule::call(function () {
    \App\Models\SmsNotification::where('created_at', '<', now()->subDays(30))
        ->whereIn('status', ['sent', 'delivered'])
        ->delete();
})->weekly();
