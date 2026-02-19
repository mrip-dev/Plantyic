<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Schedule the CloseUnbidAuctions command every 5 minutes
Schedule::command('auctions:close-unbid')->everyFiveMinutes();
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
