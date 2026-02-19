<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('main');
});

//do logowania 
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// przetwarzanie danych logowania
Route::post('/login', [AuthController::class, 'login']);

// wylogowanie
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//nie wiem co to ale sie przyda trza ogarnąć po co to
// przykładowy dashboard – chroniony
// Route::get('/dashboard', function () {
//     return 'Panel użytkownika: ' . auth()->user()->nick;
// })->middleware('auth');