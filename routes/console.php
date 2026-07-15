<?php

use App\Presentation\Queue\Jobs\AppointmentReminder24hJob;
use App\Presentation\Queue\Jobs\ManagerMorningSummaryJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new AppointmentReminder24hJob())->dailyAt(config('services.notifications.reminder_cron_time'));

Schedule::job(new ManagerMorningSummaryJob())->everyFifteenMinutes();
