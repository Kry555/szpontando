<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    //pokazuje widok z logowania
    public function showLoginForm()
    {
        return view('sign_in');
    }

    public function login(Request $request)
    {
        //czy info email i haslo wogule dotarło 
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            // 1. Najpierw sprawdzamy czy jest aktywny ban (priorytet)
            if ($user->zbanowany_do && now()->lessThan($user->zbanowany_do)) {
                $komunikat = 'Twoje konto jest zablokowane do: ' . $user->zbanowany_do;
                if ($user->powod_bana) {
                    $komunikat .= '. Powód: ' . $user->powod_bana;
                }
                return back()->with('error', $komunikat);
            }

            // 2. Jeśli ban minął, przywracamy konto (ustawiamy aktywny na 1)
            if ($user->zbanowany_do && now()->greaterThan($user->zbanowany_do)) {
                $user->update([
                    'aktywny' => 1,
                    'zbanowany_do' => null,
                    'powod_bana' => null
                ]);
            }

            // 3. Dopiero teraz sprawdzamy czy konto jest nieaktywne (np. po rejestracji)
            if ($user->aktywny == 0) {
                return back()->with('warning', 'Konto nie zostało jeszcze aktywowane. Sprawdź e-mail.');
            }
        }

        //tu sie dzieje magia z sprawdzeniem hasla i emaila czy prawidlowy
        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ])) {
            //regeneracja sesji
            $request->session()->regenerate();
            //przekierowanie po zalogowaniu
            return redirect()->intended('/');
        }
        //jesli sie nie uda
        return back()->withErrors(['email' => 'Zły email lub haslo']);
    }


    //wylogowanie

    public function logout(Request $request)
    {
        Auth::logout(); //usuwa user_id z sesji
        $request->session()->invalidate(); //unieważnia sesje
        $request->session()->regenerateToken(); //nowy token chuj wie co to

        //gdzie przekieruje
        return redirect('/');
    }
    //reset chasla
public function showForgotForm()
{
    return view('forgot-password');
}
public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $token = Str::random(64);

    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        [
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]
    );

    $link = url('/reset-password?token=' . $token . '&email=' . $request->email);

    Mail::to($request->email)->send(new ResetPasswordMail($link));

    return back()->with('status', 'Email wysłany!');
}
public function showResetForm(Request $request)
{
    return view('reset-password', [
        'token' => $request->token,
        'email' => $request->email
    ]);
}
public function resetPassword(Request $request)
{
    $expires = 60; // minuty ważności tokena
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
        'token' => 'required'
    ]);

    $record = DB::table('password_resets')
        ->where('email', $request->email)
        ->first();
$expires = 60; // minuty ważności tokena
if (!$record) {
    return back()->withErrors(['msg' => 'Nieprawidłowy token']);
}

if (Carbon::parse($record->created_at)->addMinutes($expires)->isPast()) {
    return back()->withErrors(['msg' => 'Token wygasł']);
}

if (!Hash::check($request->token, $record->token)) {
    return back()->withErrors(['msg' => 'Nieprawidłowy token']);
}

    // update user password
    DB::table('users')
        ->where('email', $request->email)
        ->update([
            'password' => Hash::make($request->password)
        ]);

    // delete token
    DB::table('password_resets')->where('email', $request->email)->delete();

    // wylogowanie innych sesji 
    Auth::logoutOtherDevices($request->password);

    // wylogowanie aktualnej sesji
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // redirect
    return redirect('/login')->with('status', 'Hasło zmienione! Zaloguj się ponownie.');
}
// emial weryfikacyjny
public function verifyEmail(Request $request)
{
    $record = DB::table('email_verifications')
        ->where('email', $request->email)
        ->first();

    if (!$record || !Hash::check($request->token, $record->token)) {
        return "Nieprawidłowy link";
    }

    DB::table('users')
        ->where('email', $request->email)
        ->update([
            'aktywny' => 1
        ]);

    DB::table('email_verifications')
        ->where('email', $request->email)
        ->delete();

    return redirect('/login')->with('status', 'Konto aktywowane!');
}
}
