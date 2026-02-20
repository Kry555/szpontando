<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use mysqli;

class RegisterController extends Controller
{
    public function show()
    {
        return view('sign_up'); //widok z form
    }

    public function register(Request $request)
    {
        echo " Start rejestracji<br>";
        //sprawdza cz dane git
        $request->validate([
            'nick' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        echo " Wszystko poprawne przeszła<br>";



        $user = User::create([
            'nick' => $request->nick,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'czy_admin' => 0,
        ]);
        echo " debil utworzony, ID: " . $user->id . "<br>";
        auth()->login($user);
        echo "chuj zalogowany<br>";
        return redirect('/')->with('success', 'Konto jebnięte wariacie');
    }
}
