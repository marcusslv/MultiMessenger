<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/message/send', [\App\Http\Controllers\MessageController::class, 'send'])
    ->name('message.send');
