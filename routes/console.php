<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\CheckMissedTimesheets;
use App\Console\Commands\CleanupTimesheetReminders;
use App\Console\Commands\MigrateProjectJsonData;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('migrate:json-data', function () {
    $this->call('app:migrate-project-json-data');
})->purpose('Run JSON data migration');
