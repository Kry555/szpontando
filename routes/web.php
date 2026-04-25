<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OfertyAddController;
use App\Http\Controllers\SetProfilController;
use App\Http\Controllers\MyOfertController;
use App\Http\Controllers\WorkOfertController;
use App\Http\Controllers\OcenaController;



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

Route::get('/work-ofert', [WorkOfertController::class, 'showOfert'])->name('work_ofert');
Route::post('/cancel-zgloszenie', [WorkOfertController::class, 'cancelZgloszenie'])
    ->name('cancelZgloszenie.post')
    ->middleware('auth');
//sortowanie po miescie
Route::get('/miasta', [OfertyController::class, 'miasta'])->name('miasta');

// Negocjacje terminów - Właściciel (Moje Oferty)
Route::post('/my-ofert/set-termin', [MyOfertController::class, 'setTerminOwner'])->name('setTerminOwner');
Route::post('/my-ofert/change-termin', [MyOfertController::class, 'changeTerminOwner'])->name('changeTerminOwner');
Route::post('/my-ofert/accept-termin', [MyOfertController::class, 'acceptTerminOwner'])->name('acceptTerminOwner');

// Negocjacje terminów - Wykonawca (Twoje Zgłoszenia)
Route::post('/work-ofert/accept-termin', [WorkOfertController::class, 'acceptTerminWorker'])->name('acceptTerminWorker');
Route::post('/work-ofert/change-termin', [WorkOfertController::class, 'changeTerminWorker'])->name('changeTerminWorker');

// Oceny
Route::post('/wystaw-ocene', [OcenaController::class, 'store'])->name('ocena.store')->middleware('auth');
Route::get('/ranking', [OfertyController::class, 'ranking'])->name('ranking');
