<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OfertyAddController;

// Route::get('/', function () {
//     return view('main');
// })->name('main');
use App\Http\Controllers\OfertyController;

Route::get('/', [OfertyController::class, 'oferty'])->name('main');

Route::post('/wybierz', [OfertyController::class, 'wybierz'])->name('oferta.wybierz');
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

//dodawanie oferty 
Route::get('/oferty-add', [OfertyAddController::class, 'show_form'])->name('oferty-add');
Route::post('/oferty-add', [OfertyAddController::class, 'add_ofert'])->name('add_oferta.post');
