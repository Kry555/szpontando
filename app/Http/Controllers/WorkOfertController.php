<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WorkOfertController extends Controller
{
    public function showOfert()
    {
        // Sprawdzenie czy użytkownik jest zalogowany
        if (!Auth::check()) {
            return view('main', ['myofert' => '']);
        }

        $id = Auth::user()->id_profil;
        $zgloszenia = DB::table('zgloszenia')
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->join('profil', 'profil.id_profil', '=', 'oferty.id_profil_owner')
            ->where('zgloszenia.id_profil_wykonawca', $id)
            ->where('oferty.status', 'aktywna')
            ->select(
                'zgloszenia.id_zgloszenia',
                'zgloszenia.wiadomosc',
                'zgloszenia.zatwierdzone',
                'zgloszenia.status as zgloszenie_status',

                'zgloszenia.termin_zaakceptowany_wlasciciel',
                'zgloszenia.termin_zaakceptowany_wykonawca',
                'zgloszenia.proponowany_termin',
                'zgloszenia.ostateczny_termin',

                'oferty.id_oferty',
                'oferty.adres',
                'oferty.typ',
                'oferty.cena',
                'oferty.do_kiedy_wazne',
                'oferty.opis',
                'oferty.status as oferta_status',

                'profil.nick',
                'profil.imie',
                'profil.nazwisko',
                'profil.data_ur',          // <-- dodane
                'profil.miasto',
                'profil.email_kontaktowy',
                'profil.ocena',
                'profil.profilowe',
                'profil.sex'
            )
            ->get();


        $zgloszeniaWybrane = DB::table('zgloszenia')
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->join('profil', 'profil.id_profil', '=', 'oferty.id_profil_owner')
            ->where('zgloszenia.id_profil_wykonawca', $id)
            ->where('zgloszenia.zatwierdzone', 1)
            ->select(
                'zgloszenia.id_zgloszenia',
                'zgloszenia.id_oferty',
                'zgloszenia.id_profil_wykonawca',
                'zgloszenia.wiadomosc',
                'zgloszenia.status',
                'zgloszenia.proponowany_termin',
                'zgloszenia.ostateczny_termin',
                'zgloszenia.termin_zaakceptowany_wlasciciel',
                'zgloszenia.termin_zaakceptowany_wykonawca',
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
                'oferty.status'
            )
            ->get();

        // Sprawdź czy wystawiono oceny
        foreach ($zgloszeniaWybrane as $z) {
            if ($z->ostateczny_termin && \Carbon\Carbon::parse($z->ostateczny_termin)->isPast()) {
                $z->juz_oceniono = DB::table('oceny')
                    ->where('id_zgloszenia', $z->id_zgloszenia)
                    ->where('id_profil_autor', $id)
                    ->exists();
            }
        }

        return view('workOfert', [
            'zgloszenia' => $zgloszenia,
            'zgloszeniaWybrane' => $zgloszeniaWybrane
        ]);
    }
    public function cancelZgloszenie(Request $request)
    {
        $request->validate([
            'id_zgloszenia' => 'required|integer'
        ]);

        $zgloszenie = DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->first();

        if (!$zgloszenie) {
            return back()->with('error', 'Zgłoszenie nie istnieje');
        }

        // blokada dla statusów, których nie można anulować
        if (in_array($zgloszenie->status, ['anulowane', 'zatwierdzone', 'wykonane'])) {
            return back()->with('error', 'Nie można anulować tego zgłoszenia');
        }

        DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->update([
                'status' => 'anulowane'
            ]);

        return back()->with('success', 'Zgłoszenie anulowane');
    }

    public function acceptTerminWorker(Request $request)
    {
        $request->validate(['id_zgloszenia' => 'required|integer']);

        $idProfil = Auth::user()->id_profil;
        $zgloszenie = DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->where('id_profil_wykonawca', $idProfil)
            ->first();

        if (!$zgloszenie) {
            return back()->with('error', 'Zgłoszenie nie istnieje lub nie należy do Ciebie.');
        }

        if (empty($zgloszenie->proponowany_termin)) {
            return back()->with('error', 'Właściciel nie zaproponował jeszcze terminu.');
        }

        DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->update([
                'termin_zaakceptowany_wykonawca' => 1,
                'ostateczny_termin' => $zgloszenie->proponowany_termin // Skoro obie strony akceptują, termin staje się ostateczny
            ]);

        // Pobierz id_profil_owner oferty, do której należy zgłoszenie
        $oferta = DB::table('oferty')
            ->join('zgloszenia', 'oferty.id_oferty', '=', 'zgloszenia.id_oferty')
            ->where('zgloszenia.id_zgloszenia', $request->id_zgloszenia)
            ->select('oferty.id_profil_owner')
            ->first();

        if ($oferta) {
            // Znajdź użytkownika (właściciela)
            $ownerUser = DB::table('users')
                ->where('id_profil', $oferta->id_profil_owner)
                ->first();

            if ($ownerUser) {
                // Formatowanie daty dla czytelności
                $dataFormatted = \Carbon\Carbon::parse($zgloszenie->proponowany_termin)->format('Y-m-d H:i');

                DB::table('powiadomienia')->insert([
                    'tytul' => 'Termin zaakceptowany',
                    'text' => 'Wykonawca zaakceptował Twój termin: ' . $dataFormatted,
                    'odzcytane' => 0,
                    'id_user' => $ownerUser->id,
                ]);
            }
        }

        return back()->with('success', 'Termin został zaakceptowany!');
    }

    public function changeTerminWorker(Request $request)
    {
        $request->validate([
            'id_zgloszenia' => 'required|integer',
            'termin' => 'required|date|after:now'
        ]);

        $idProfil = Auth::user()->id_profil;
        $zgloszenie = DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->where('id_profil_wykonawca', $idProfil)
            ->first();

        if (!$zgloszenie) {
            return back()->with('error', 'Nie masz uprawnień do zmiany tego zgłoszenia.');
        }

        DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->update([
                'proponowany_termin' => $request->termin,
                'termin_zaakceptowany_wykonawca' => 1,
                'termin_zaakceptowany_wlasciciel' => 0, // Teraz właściciel musi zaakceptować nową datę
                'ostateczny_termin' => null
            ]);

        // Pobierz id_profil_owner oferty, do której należy zgłoszenie
        $oferta = DB::table('oferty')
            ->join('zgloszenia', 'oferty.id_oferty', '=', 'zgloszenia.id_oferty')
            ->where('zgloszenia.id_zgloszenia', $request->id_zgloszenia)
            ->select('oferty.id_profil_owner')
            ->first();

        if ($oferta) {
            // Pobierz id użytkownika (właściciela) na podstawie id_profil_owner
            $ownerUser = DB::table('users')
                ->where('id_profil', $oferta->id_profil_owner)
                ->first();

            // Pobierz nick wykonawcy, który zaproponował termin
            $workerNick = Auth::user()->nick;

            if ($ownerUser) {
                // Formatowanie daty
                $dataFormatted = \Carbon\Carbon::parse($request->termin)->format('Y-m-d H:i');

                // Dodaj powiadomienie dla właściciela
                DB::table('powiadomienia')->insert([
                    'tytul' => 'Nowa propozycja terminu',
                    'text' => 'Wykonawca ' . $workerNick . ' zaproponował nowy termin: ' . $dataFormatted . '.',
                    'odzcytane' => 0,
                    'id_user' => $ownerUser->id,
                ]);
            }
        }

        return back()->with('success', 'Zaproponowałeś inny termin właścicielowi.');
    }
}
