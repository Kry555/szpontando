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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function show()
    {
        return view('sign_up'); //widok z form
    }

    public function register(Request $request)
    {

        //sprawdza cz dane git
        $request->validate([
            'nick' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'tapczan' => 'accepted',
        ], [
            'tapczan.accepted' => 'skibidi ',
        ]);


        $user = null;
        $token = null;
        DB::transaction(function () use ($request, &$user, &$token) {

            // Tworzymy profil (AUTO_INCREMENT id_profil)
            $profil_id = DB::table('profil')->insertGetId([
                'nick' => $request->nick,
                'profilowe' => 'default.jpg',
            ]);

            // Tworzymy użytkownika powiązanego z tym id_profil
            $user = User::create([
                'nick' => $request->nick,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'czy_admin' => 0,
                'id_profil' => $profil_id, 
                'gender' => $request->gender,
                'aktywny' => 0, // Konto nieaktywne do czasu aktywacji
            ]);
            $token = Str::random(64);

            DB::table('email_verifications')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
            ]);

        });
        $link = url('/verify-email?token=' . $token . '&email=' . $request->email);

        Mail::to($request->email)->send(new \App\Mail\VerifyEmailMail($link));
        
        

        echo " użytkownik utworzony, ID: " . $user->id . "<br>";
        return redirect('/')->with('success', 'Konto utworzone, oczekuje na aktywację');
    }
}
