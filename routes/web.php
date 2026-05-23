<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OfertyAddController;
use App\Http\Controllers\SetProfilController;
use App\Http\Controllers\MyOfertController;
use App\Http\Controllers\WorkOfertController;
use App\Http\Controllers\OcenaController;
use App\Http\Controllers\AdminController;



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

// forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// reset password
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/ranking', [OfertyController::class, 'ranking'])->name('ranking');
// email weryfikacyjny
Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->name('verify.email');

// Grupa tras administratora
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/niskie-oceny', [AdminController::class, 'niskieOceny'])->name('niskie_oceny');
    Route::post('/ban-user', [AdminController::class, 'banujUzytkownika'])->name('ban_user');
    Route::post('/unban-user', [AdminController::class, 'odbanujUzytkownika'])->name('unban_user');
    Route::get('/zgloszenia', [AdminController::class, 'zgloszoneOferty'])->name('zgloszenia');
    Route::post('/rozpatrz-zgloszenie', [AdminController::class, 'rozpatrzZgloszenie'])->name('rozpatrz_zgloszenie');
    Route::get('/user-stats', [AdminController::class, 'statystykiUzytkownika'])->name('user_stats');
    Route::post('/ban-oferta', [AdminController::class, 'banujOferte'])->name('ban_oferta');
    Route::post('/zglos-oferte', [OfertyController::class, 'zglosOferte'])->name('zglos_oferte');
    Route::get('/logs', [AdminController::class, 'dziennikZdarzen'])->name('logs');
});
