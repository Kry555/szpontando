<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;


Route::get('/', function () {
    return view('main');
})->name('main');

//do logowania 
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// przetwarzanie danych logowania
Route::post('/login', [AuthController::class, 'login']);

// wylogowanie
Route::get('/logoutt', function () {
    return view('logout-szablon');
})->name('logoutt');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Wyświetlenie formularza rejestracji
Route::get('/register', [RegisterController::class, 'show'])->name('register.show');

// Obsługa POST – tworzenie użytkownika
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
//nie wiem co to ale sie przyda trza ogarnąć po co to
// przykładowy dashboard – chroniony
// Route::get('/dashboard', function () {
//     return 'Panel użytkownika: ' . auth()->user()->nick;
// })->middleware('auth');