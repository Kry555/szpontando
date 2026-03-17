<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OfertyAddController;
use App\Http\Controllers\SetProfilController;
use App\Http\Controllers\MyOfertController;



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
Route::get('/add-ofert', [OfertyAddController::class, 'show_form'])->name('add_ofert');
Route::post('/add-ofert', [OfertyAddController::class, 'add_ofert'])->name('add_ofert.post');
//panel do zmieniania 
Route::get('/set-profil', [SetProfilController::class, 'showProfil'])->name('set_profil');
Route::post('/set-profil', [SetProfilController::class, 'editProfil'])->name('set_profil.post');

Route::get('/my-ofert', [MyOfertController::class, 'showOfert'])->name('my_ofert');
Route::post('/accept-ofert', [MyOfertController::class, 'acceptOfert'])->name('acceptOfert.post');
Route::post('/oferta-zakoncz', [MyOfertController::class, 'zakonczOfert'])->name('zakonczOfert.post');
Route::post('/edit-offer', [MyOfertController::class, 'editOffer'])->name('edit_offer.post')->middleware('auth');
