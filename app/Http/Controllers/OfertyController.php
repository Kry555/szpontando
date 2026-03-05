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
            )->leftjoin('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil')->get();
            $komuch = 'Zaloguj sie by czytać powiadomienia mały fiucie';
            return view('main', ['oferty_przeglandarka' => $dane, 'notf' => $komuch]);
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
        )->leftjoin('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil')->where('oferty.id_profil_owner', '!=', $id)->orderBy('oferty.stworzone', 'desc')->get();
        // Pobieramy ID ofert, do których użytkownik się zgłosił
        $aktywne = $id ? DB::table('zgloszenia')
            ->where('id_profil_wykonawca', $id)
            ->pluck('id_oferty')
            ->toArray() : [];

        $komuch = DB::table('powiadomienia')->select(
            'tytul',
            'text',
        )->where('id_user', '=', $id)->where('odzcytane', '=', 0)->get();

        return view('main', ['oferty_przeglandarka' => $dane, 'Zgloszenia_aktywne' => $aktywne, 'notf' => $komuch]);
    }

    public function wybierz(Request $request)
    {

        $request->validate([
            'oferta_id' => 'required|integer|exists:oferty,id_oferty',
            'wiadomosc' => 'nullable|string|max:1000',
        ]);

        $ofertaId = $request->oferta_id;
        $id_zatwierdzajacego = auth()->user()->id_profil;
        $wiadomosc = $request->wiadomosc;

        $nick = DB::table('users')
            ->where('id_profil', $id_zatwierdzajacego)->value('nick');

        $id_wlasciciel = DB::table('oferty')
            ->where('id_oferty', $ofertaId)
            ->value('id_profil_owner');

        if ($id_wlasciciel != $id_zatwierdzajacego) {
            DB::table('zgloszenia')->insert([
                'id_oferty' => $ofertaId,
                'id_profil_wykonawca' => $id_zatwierdzajacego,
                'wiadomosc' => $wiadomosc,
                'zatwierdzone' => 0,
            ]);



            DB::table('powiadomienia')->insert([
                'tytul' => 'nowe zgloszenie do twojej oferty',
                'text' => 'uzytkownik ' . $nick . ' zglosil sie do twojego zgloszenia',
                'odzcytane' => 0,
                'id_user' => $id_wlasciciel
            ]);
        }

        return redirect()->route('main')->with('modal_success', $request->oferta_id);
    }
}
