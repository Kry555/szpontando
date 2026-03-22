<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfertyController extends Controller
{
    public function oferty(Request $request)
    {
        //----jesli nie zalogowany to oferty----
        if (!Auth::check()) {
            $query = DB::table('oferty')->select(
    'profil.imie',
    'oferty.id_oferty',
    'profil.nazwisko',
    'profil.profilowe',
    'oferty.adres',
    'oferty.typ',
    'oferty.cena',
    'oferty.do_kiedy_wazne',
    'oferty.opis',
    'oferty.created_at'
)->leftJoin('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil');

// 🔽 FILTER
if ($request->filled('typ')) {
    $query->where('oferty.typ', $request->typ);
}

$dane = $query->orderBy('oferty.created_at', 'desc')->get();
            //musi byc bo blad
            $komuch = '';

            return view('main', ['oferty_przeglandarka' => $dane, 'notf' => $komuch]);
        }
        $id = Auth::user()->id_profil;

        //----oferty dla zalogowanego-----

        // profil.imie,profil.nazwisko,profil.profilowe,oferty:adres,typ,cena,do_kidey_wazne,opis,stworzone
        $query = DB::table('oferty')->select(
    'profil.imie',
    'oferty.id_oferty',
    'profil.nazwisko',
    'profil.profilowe',
    'oferty.adres',
    'oferty.typ',
    'oferty.cena',
    'oferty.do_kiedy_wazne',
    'oferty.opis',
    'oferty.created_at'
)->leftJoin('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil')
 ->where('oferty.id_profil_owner', '!=', $id);

//  FILTER
if ($request->filled('typ')) {
    $query->where('oferty.typ', $request->typ);
}

$dane = $query->orderBy('oferty.created_at', 'desc')->get();
        //----id do kturych sie zglosil----
        $aktywne = $id ? DB::table('zgloszenia')
            ->where('id_profil_wykonawca', $id)
            ->pluck('id_oferty')
            ->toArray() : [];

        //----odczyt powiadomien----
        $komuch = DB::table('powiadomienia')->select(
            'tytul',
            'text',
        )->where('id_user', '=', $id)->where('odzcytane', '=', 0)->get();

        $teraz = date('Y-m-d H:i:s');

        foreach ($dane as $dan) {
            if ($dan->do_kiedy_wazne <= $teraz) {
                DB::table('oferty')->where('id_oferty', $dan->id_oferty)->update([
                    'status' => 'wygaslo'
                ]);
            }
        }

        return view('main', ['oferty_przeglandarka' => $dane, 'Zgloszenia_aktywne' => $aktywne, 'notf' => $komuch]);
    }

    public function wybierz(Request $request)
    {
        //----walidacja tego co pszyszlo----
        $request->validate([
            'oferta_id' => 'required|integer|exists:oferty,id_oferty',
            'wiadomosc' => 'nullable|string|max:1000',
        ]);

        //----zmienne----
        $ofertaId = $request->oferta_id;


        //--zabezpieczenie nie mozna sie zglosic do nie aktywnej oferty 
        $status = DB::table('oferty')
            ->where('id_oferty', $ofertaId)
            ->value('status');

        if ($status !== 'aktywna') {
            return back()->with('error', 'Nie można zgłosić się do tej oferty');
        }

        $id_zatwierdzajacego = Auth::user()->id_profil;
        $wiadomosc = $request->wiadomosc;

        $nick = DB::table('users')
            ->where('id_profil', $id_zatwierdzajacego)->value('nick');

        $id_wlasciciel = DB::table('oferty')
            ->where('id_oferty', $ofertaId)
            ->value('id_profil_owner');

        //---- zabezpieczenie by nie zglosic sie do swojej oferty(!!pod tresc zadania!!)----
        if ($id_wlasciciel != $id_zatwierdzajacego) {
            DB::table('zgloszenia')->insert([
                'id_oferty' => $ofertaId,
                'id_profil_wykonawca' => $id_zatwierdzajacego,
                'wiadomosc' => $wiadomosc,
                'zatwierdzone' => 0,
                'status' => 'aktywne',
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

    public function zakonczOfert(Request $request)
    {
        $request->validate([
            'id_oferty' => 'required|integer'
        ]);

        DB::table('oferty')
            ->where('id_oferty', $request->id_oferty)
            ->update([
                'status' => 'anulowane'
            ]);

        return back()->with('success', 'Oferta została zakończona');
    }
}
