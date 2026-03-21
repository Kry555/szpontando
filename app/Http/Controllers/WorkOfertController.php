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
            ->join('profil', 'profil.id_profil', '=', 'zgloszenia.id_profil_wykonawca')
            ->where('zgloszenia.id_profil_wykonawca', $id)
            ->where('zgloszenia.zatwierdzone', 1)
            ->select(
                'zgloszenia.id_zgloszenia',
                'zgloszenia.id_oferty',
                'zgloszenia.id_profil_wykonawca',
                'zgloszenia.wiadomosc',
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
}
