<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:import-old-arthall-artists')->everyThirtyMinutes()->appendOutputTo(storage_path('logs/scheduler.log'));
Schedule::command('app:import-synergy-artists')->everyThirtyMinutes()->appendOutputTo(storage_path('logs/scheduler.log'));
