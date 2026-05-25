<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Infrastructure\Events\UserAttemptLoginOutsideAllowedTime;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/broadcast', function () {
    broadcast(new UserAttemptLoginOutsideAllowedTime());

    return 'Broadcast event has been sent!';
});


Broadcast::routes();
require base_path('/routes/channels.php');
