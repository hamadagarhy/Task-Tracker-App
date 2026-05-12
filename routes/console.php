<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:generate-recurring-tasks')
    ->everyMinute()
    ->runInBackground()
    ->sendOutputTo(storage_path('logs/recurring-tasks.log'));
Schedule::command('app:archive-expired-recurring-tasks')->daily();


