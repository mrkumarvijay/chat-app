<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth');
});

Route::get('/chat', function () {
    return view('chat');
});
