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
        // Sprawdzenie czy użytkownik jest zalogowany
        if (!Auth::check()) {
            return view('main', ['myofert' => '']);
        }

        $id = Auth::user()->id_profil;


        $oferty = DB::table('oferty')
            ->where('id_profil_owner', $id)
            ->get();


        $zgloszenia = DB::table('zgloszenia')
            ->select(
                'zgloszenia.id_zgloszenia',
                'zgloszenia.id_oferty',
                'zgloszenia.id_profil_wykonawca',
                'zgloszenia.wiadomosc',
                'zgloszenia.zatwierdzone',
                'zgloszenia.status',
                'profil.nick',
                'profil.imie',
                'profil.nazwisko',
                'profil.miasto',
                'profil.email_kontaktowy',
                'profil.ocena',
                'profil.profilowe',
                'profil.sex',
                'oferty.adres',
                'oferty.typ',
                'oferty.cena',
                'oferty.do_kiedy_wazne',
                'oferty.opis',
                'oferty.status as oferta_status'
            )
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->join('profil', 'profil.id_profil', '=', 'zgloszenia.id_profil_wykonawca')
            ->where('oferty.id_profil_owner', $id)
            ->whereIn('zgloszenia.status', ['aktywne', 'zatwierdzone']) // <- jawnie
            ->get();


        return view('myOfert', [
            'oferty' => $oferty,
            'zgloszenia' => $zgloszenia
        ]);
    }
    public function acceptOfert(Request $request)
    {
        $request->validate([
            'id_oferty' => 'required|integer',
            'id_zgloszenia' => 'required|integer'
        ]);

        $oferta = DB::table('oferty')
            ->where('id_oferty', $request->id_oferty)
            ->first();

        // Blokada dla wszystkiego, co nie jest aktywne
        if ($oferta->status !== 'aktywna') {
            return back()->with('error', 'Nie można zaakceptować zgłoszenia do oferty, która nie jest aktywna.');
        }
        // sprawdzenie czy ktoś już został zaakceptowany
        $exists = DB::table('zgloszenia')
            ->where('id_oferty', $request->id_oferty)
            ->where('zatwierdzone', 1)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Ktoś już został zaakceptowany do tej oferty');
        }

        DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->update([
                'zatwierdzone' => 1,
                'status' => 'zatwierdzone'
            ]);

        // pobierz dane wykonawcy
        $zgloszenie = DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->first();

        // znajdź usera po id_profil
        $user = DB::table('users')
            ->where('id_profil', $zgloszenie->id_profil_wykonawca)
            ->first();

        // powiadomienie
        DB::table('powiadomienia')->insert([
            'tytul' => 'twoje zgłoszenie zostało zaakceptowane',
            'text' => 'Twoje zgłoszenie do oferty zostało zaakceptowane',
            'odzcytane' => 0,
            'id_user' => $user->id
        ]);
        // jescze tabela oferty status ma byc przyjęte
        DB::table('oferty')->where('id_oferty', $request->id_oferty)->update([
            'status' => 'zaakceptowana'
        ]);


        return back()->with('success', 'Zgłoszenie zaakceptowane');
    }
    public function zakonczOfert(Request $request)
    {
        $request->validate([
            'id_oferty' => 'required|integer'
        ]);

        // Zaktualizuj status oferty
        DB::table('oferty')
            ->where('id_oferty', $request->id_oferty)
            ->update([
                'status' => 'anulowane'  // lub 'zakończone', jeśli wolisz taką nazwę
            ]);

        // Zaktualizuj status wszystkich zgłoszeń powiązanych z tą ofertą
        DB::table('zgloszenia')
            ->where('id_oferty', $request->id_oferty)
            ->update([
                'status' => 'zakończone'
            ]);

        return back()->with('success', 'Oferta została zakończona, a zgłoszenia oznaczone jako zakończone.');
    }
    public function editOffer(Request $request)
    {
        $idProfil = Auth::user()->id_profil;

        $validated = $request->validate([
            'id_oferty' => 'required|integer|exists:oferty,id_oferty',
            'adres' => 'required|string|max:255',
            'typ' => 'required|string|max:100',
            'cena' => 'required|numeric|min:0',
            'do_kiedy_wazne' => 'required|date|after:now',
            'opis' => 'required|string|max:2000',
        ]);

        $oferta = DB::table('oferty')
            ->where('id_oferty', $validated['id_oferty'])
            ->where('id_profil_owner', $idProfil)
            ->first();

        if (!$oferta) {
            return back()->with('error', 'Nie masz prawa edytować tej oferty.');
        }

        DB::table('oferty')
            ->where('id_oferty', $validated['id_oferty'])
            ->update([
                'adres' => $validated['adres'],
                'typ' => $validated['typ'],
                'cena' => $validated['cena'],
                'do_kiedy_wazne' => $validated['do_kiedy_wazne'],
                'opis' => $validated['opis'],
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Oferta została zaktualizowana.');
    }
}
