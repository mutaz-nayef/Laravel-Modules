<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Broadcast::routes();
require base_path('/routes/channels.php');
