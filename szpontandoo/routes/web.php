<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});
Route::get('/login', function () {
    return view('sign_in');
});
Route::get('/rejestracja', function () {
    return view('sign_up');
});
