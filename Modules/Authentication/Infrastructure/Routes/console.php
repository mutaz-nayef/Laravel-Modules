<?php


use Illuminate\Support\Facades\Schedule;


Schedule::command('logout-users-not-allowed-time')
    ->dailyAt('14:10')
    ->runInBackground();
