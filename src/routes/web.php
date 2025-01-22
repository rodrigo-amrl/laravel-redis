<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/redis', function () {
    Redis::set('name', 'Taylor');
    $value = Redis::get('name');
    return $value;
});
