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
}
