<?php

use App\Models\NotificationChannel;
use App\Models\NotificationTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notifications', function () {

    return view('notifications-edit', [
        'notificationChannels' => NotificationChannel::get(),
        'notificationTypes' => NotificationTypes::all()->groupBy('group')
    ]);
});

Route::patch('/notifications.update', function (Request $request) {

    dd($request->all());

})->name('notifications.update');
Broadcast::routes();
require base_path('/routes/channels.php');
