<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use mysqli;
use Illuminate\Support\Facades\DB;

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
            'tapczan' => 'accepted',
        ], [
            'tapczan.accepted' => 'Jesteś ruskim botem wypierdalaj',
        ]);
        echo " Wszystko poprawne przeszła<br>";

        $user = null;

        DB::transaction(function () use ($request, &$user) {
            $ostatni_profil = DB::table('profil')->lockForUpdate()->max('id_profil');
            $ostatni_profil = $ostatni_profil ? $ostatni_profil + 1 : 1;

            $user = User::create([
                'nick' => $request->nick,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'czy_admin' => 0,
                'id_profil' => $ostatni_profil,
            ]);
            DB::table('profil')->insert([
                'nick' => $request->nick,
            ]);
        });

        echo " debil utworzony, ID: " . $user->id . "<br>";
        // auth()->login($user) podswietla mi blad na tym
        Auth::login($user);
        echo "chuj zalogowany<br>";
        return redirect('/')->with('success', 'Konto jebnięte wariacie');
    }
}
