<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command('app:renew-memberships')->dailyAt('00:01');
Schedule::command('app:expire-active-packages')->dailyAt('00:02');
Schedule::command('app:expire-active-membersips')->dailyAt('00:03');
Schedule::command('app:activate-upcoming-membersips')->dailyAt('00:04');
