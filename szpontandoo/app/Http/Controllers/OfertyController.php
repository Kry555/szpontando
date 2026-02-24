<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OfertyController extends Controller
{
    public function oferty()
    {
        if (!auth()->check()) {
            return view('main');
        }
        $id = auth()->user()->id_profil;
        //SELECT * FROM oferty WHERE  != 1;

        // profil.imie,profil.nazwisko,profil.profilowe,oferty:adres,typ,cena,do_kidey_wazne,opis,stworzone
        $dane = DB::table('oferty')->select(
            'profil.imie',
            'oferty.id_oferty',
            'profil.nazwisko',
            'profil.profilowe',
            'oferty.adres',
            'oferty.typ',
            'oferty.cena',
            'oferty.do_kiedy_wazne',
            'oferty.opis',
            'oferty.stworzone'
        )->join('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil')->where('oferty.id_profil_owner', '!=', $id)->get();

        return view('main', ['oferty_przeglandarka' => $dane]);
    }

    public function wybierz(Request $request)
    {
        $ofertaId = $request->oferta_id;
        $id_zatwierdzajacego = auth()->user()->id_profil;
        DB::table('zgloszenia')->insert([
            'id_oferty' => $ofertaId,
            'id_profil_wykonawca' => $id_zatwierdzajacego,
            'zatwierdzone' => 0,
        ]);
    }
}
