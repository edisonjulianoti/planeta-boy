<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Recalcular selo verificado automaticamente — diário às 4h
Schedule::command('profiles:recalculate-verified')
    ->dailyAt('04:00')
    ->description('Recalcular verified para perfis não-manuais');
