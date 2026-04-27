<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-weather', function () {
    return 'test';
});


Route::fallback(function () {
    return response()->json([
        'success' => false,
        'data' => [],
        'errors' => 'Page not found',
        'message' => '',
        'status' => 404
    ], 404);

});
