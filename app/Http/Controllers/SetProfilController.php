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
            'profilowe' => 'nullable|image|max:2048', // max 2MB
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

        DB::transaction(function () use ($request, $valid, $idprofil) {

            // Obsługa uploadu zdjęcia
            $profiloweName = null;
            $currentProfil = DB::table('profil')->where('id_profil', $idprofil)->first();

            if ($request->hasFile('profilowe')) {
                $file = $request->file('profilowe');
                $profiloweName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/profilowe'), $profiloweName);
            } else {
                // jeśli nie wybrano nowego zdjęcia, zostaw stary plik
                $profiloweName = $currentProfil->profilowe ?? null;
            }

            // Aktualizacja tabeli profil
            DB::table('profil')->where('id_profil', $idprofil)->update([
                'profilowe' => $profiloweName,
                'nick' => $valid['nick'],
                'imie' => $valid['imie'],
                'nazwisko' => $valid['nazwisko'],
                'data_ur' => $valid['data_ur'],
                'miasto' => $valid['miasto'],
                'email_kontaktowy' => $valid['email_kontaktowy'],
                'sex' => $valid['gender'],
            ]);

            // Aktualizacja tabeli users (tylko nick)
            DB::table('users')->where('id_profil', $idprofil)->update([
                'nick' => $valid['nick'],
            ]);
        });

        return back()->with('email_sent', true);
    }
}
