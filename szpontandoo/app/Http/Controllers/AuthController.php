<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        //tu sie dzieje magia z sprawdzeniem hasla i emaila czy prawidlowy
        if (Auth::attempt([
            'email' => $credentials['email'],
            'haslo' => $credentials['password']
        ])) {
            //regeneracja sesji
            $request->session()->regenerate();
            //przekierowanie po zalogowaniu
            return redirect()->intended('/main');
        }
        //jesli sie nie uda
        return back()->withErrors(['email' => 'Debilu zły email lub haslo jebal cie pies']);
    }


    //wylogowanie

    public function logout(Request $request)
    {
        Auth::logout(); //usuwa user_id z sesji
        $request->session()->invalidate(); //unieważnia sesje
        $request->session()->regenerateToken(); //nowy token chuj wie co to

        //gdzie przekieruje
        return redirect('/main');
    }
}
