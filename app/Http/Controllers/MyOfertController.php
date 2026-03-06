<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MyOfertController extends Controller
{
    public function showOfert()
    {
        if (!Auth::check()) {

            $las = '';

            return view('main', ['myofert' => $las]);
        }
        $id = Auth::user()->id_profil;
        $las = DB::table('zgloszenia')
            ->select(
                'zgloszenia.wiadomosc',
                'zgloszenia.zatwierdzone',
                'oferty.adres',
                'oferty.typ',
                'oferty.cena',
                'oferty.do_kiedy_wazne',
                'oferty.opis',
                'oferty.status',
                'profil.nick',
                'profil.imie',
                'profil.nazwisko',
                'profil.data_ur',
                'profil.miasto',
                'profil.email_kontaktowy',
                'profil.ocena',
                'profil.profilowe',
                'profil.sex'
            )
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->join('profil', 'profil.id_profil', '=', 'zgloszenia.id_profil_wykonawca')
            ->where('oferty.id_profil_owner', '=', $id)->get();
        return view('myOfert', ['myofert' => $las]);
    }
}
