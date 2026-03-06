<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SetProfilController extends Controller
{
    public function showProfil()
    {
        if (!Auth::check()) {
            $profil = "";
            return view('set-profil', ['profil' => $profil]);
        }

        $id_zalog = Auth::user()->id_profil;

        $profil = DB::table('profil')->select(
            'profilowe',
            'nick',
            'imie',
            'nazwisko',
            'sex',
            'data_ur',
            'miasto',
            'email_kontaktowy',
            'ocena',
        )->where('id_profil', '=', $id_zalog)->first();

        return view('set-profil', ['profil' => $profil]);
    }
    public function editProfil(Request $request)
    {
        $idprofil = Auth::user()->id_profil;
        $valid = $request->validate([
            'profilowe' => 'nullable|string|max:255', // można pozostawić puste, jeśli obrazek będzie opcjonalny
            'nick' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) use ($idprofil) {
                    // sprawdzenie unikalności w profil
                    $existsProfil = DB::table('profil')
                        ->where('nick', $value)
                        ->where('id_profil', '!=', $idprofil)
                        ->exists();
                    if ($existsProfil) {
                        $fail('Ten nick jest już zajęty w profil.');
                    }


                    $existsUsers = DB::table('users')
                        ->where('nick', $value)
                        ->where('id_profil', '!=', $idprofil)
                        ->exists();
                    if ($existsUsers) {
                        $fail('Ten nick jest już zajęty w users.');
                    }
                }
            ],
            'imie' => 'required|string|max:50',
            'nazwisko' => 'required|string|max:50',
            'data_ur' => 'required|date|before:today',
            'miasto' => 'required|string|max:100',
            'email_kontaktowy' => 'required|email|max:100',
            'gender' => 'required|in:men,women,slup',
        ]);

        DB::transaction(function () use ($valid, $idprofil) {
            DB::table('profil')->where('id_profil', $idprofil)->update([
                'profilowe' => $valid['profilowe'],
                'nick' => $valid['nick'],
                'imie' => $valid['imie'],
                'nazwisko' => $valid['nazwisko'],
                'data_ur' => $valid['data_ur'],
                'miasto' => $valid['miasto'],
                'email_kontaktowy' => $valid['email_kontaktowy'],
                'sex' => $valid['gender'],
            ]);

            DB::table('users')->where('id_profil', $idprofil)->update([
                'nick' => $valid['nick'],
            ]);
        });

        return redirect()->route('set_profil');
    }
}
