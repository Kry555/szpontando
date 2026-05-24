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
                'oferty.status as oferta_status'
            )
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->join('profil', 'profil.id_profil', '=', 'zgloszenia.id_profil_wykonawca')
            ->where('oferty.id_profil_owner', $id)
            ->whereIn('zgloszenia.status', ['aktywne', 'zatwierdzone'])
            ->get();

        // Sprawdź czy wystawiono oceny
        foreach ($zgloszenia as $z) {

            if ($z->ostateczny_termin) {

                $z->juz_oceniono = DB::table('oceny')
                    ->where('id_zgloszenia', $z->id_zgloszenia)
                    ->where('id_profil_autor', $id)
                    ->exists();
            }

            // Pobranie 3 ostatnich zakończonych zleceń pracownika
            $z->ostatnie_zlecenia = DB::table('zgloszenia')
                ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')

                ->leftJoin('oceny', function ($join) {
                    $join->on('oceny.id_zgloszenia', '=', 'zgloszenia.id_zgloszenia')
                        ->where('oceny.rola', '=', 'gospodarz');
                })

                ->leftJoin('profil as autor_opinii', 'oceny.id_profil_autor', '=', 'autor_opinii.id_profil')

                ->join('profil as owner_oferty', 'oferty.id_profil_owner', '=', 'owner_oferty.id_profil')

                ->where('zgloszenia.id_profil_wykonawca', $z->id_profil_wykonawca)

                ->whereNotNull('zgloszenia.ostateczny_termin')

                ->orderBy('zgloszenia.ostateczny_termin', 'desc')

                ->limit(3)

                ->select(
                    'oferty.typ',
                    'oferty.adres',
                    'oferty.cena',
                    'oferty.do_kiedy_wazne',
                    'oferty.opis as oferta_opis',
                    'oceny.gwiazdki',
                    'oceny.opis as opinia_tekst',
                    'autor_opinii.nick as autor_nick',
                    'autor_opinii.profilowe as autor_foto'
                )
                ->get()
                ->toJson(JSON_HEX_APOS | JSON_HEX_QUOT);
        }

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

        // status oferty
        DB::table('oferty')
            ->where('id_oferty', $request->id_oferty)
            ->update([
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
                'status' => 'anulowane'
            ]);

        // Zaktualizuj status zgłoszeń
        DB::table('zgloszenia')
            ->where('id_oferty', $request->id_oferty)
            ->update([
                'status' => 'zakończone'
            ]);

        return back()->with('success', 'Oferta została zakończona.');
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

            'zdjecie_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'zdjecie_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $oferta = DB::table('oferty')
            ->where('id_oferty', $validated['id_oferty'])
            ->where('id_profil_owner', $idProfil)
            ->first();

        if (!$oferta) {
            return back()->with('error', 'Nie masz uprawnień do edycji tej oferty lub oferta nie istnieje.');
        }

        $updateData = [
            'adres' => $validated['adres'],
            'typ' => $validated['typ'],
            'cena' => $validated['cena'],
            'do_kiedy_wazne' => $validated['do_kiedy_wazne'],
            'opis' => $validated['opis'],
            'updated_at' => now(),
        ];

        // OBSŁUGA ZDJĘCIA 1
        if ($request->hasFile('zdjecie_1')) {

            // usuń stare
            if (
                $oferta->zdjecie_1 &&
                file_exists(public_path('images/oferty/' . $oferta->zdjecie_1))
            ) {
                unlink(public_path('images/oferty/' . $oferta->zdjecie_1));
            }

            $file = $request->file('zdjecie_1');

            $filename = time() . '_o1_edit.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/oferty'), $filename);

            $updateData['zdjecie_1'] = $filename;

        } elseif ($request->input('clear_zdjecie_1')) {

            if (
                $oferta->zdjecie_1 &&
                file_exists(public_path('images/oferty/' . $oferta->zdjecie_1))
            ) {
                unlink(public_path('images/oferty/' . $oferta->zdjecie_1));
            }

            $updateData['zdjecie_1'] = null;
        }

        // OBSŁUGA ZDJĘCIA 2
        if ($request->hasFile('zdjecie_2')) {

            // usuń stare
            if (
                $oferta->zdjecie_2 &&
                file_exists(public_path('images/oferty/' . $oferta->zdjecie_2))
            ) {
                unlink(public_path('images/oferty/' . $oferta->zdjecie_2));
            }

            $file = $request->file('zdjecie_2');

            $filename = time() . '_o2_edit.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/oferty'), $filename);

            $updateData['zdjecie_2'] = $filename;

        } elseif ($request->input('clear_zdjecie_2')) {

            if (
                $oferta->zdjecie_2 &&
                file_exists(public_path('images/oferty/' . $oferta->zdjecie_2))
            ) {
                unlink(public_path('images/oferty/' . $oferta->zdjecie_2));
            }

            $updateData['zdjecie_2'] = null;
        }

        DB::table('oferty')
            ->where('id_oferty', $validated['id_oferty'])
            ->where('id_profil_owner', $idProfil)
            ->where('status', 'aktywna')
            ->update($updateData);

        return back()->with('success', 'Oferta została zaktualizowana.');
    }

    public function setTerminOwner(Request $request)
    {
        $request->validate([
            'id_zgloszenia' => 'required|integer',
            'termin' => 'required|date|after:now'
        ]);

        $idProfil = Auth::user()->id_profil;

        $validOwner = DB::table('zgloszenia')
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->where('zgloszenia.id_zgloszenia', $request->id_zgloszenia)
            ->where('oferty.id_profil_owner', $idProfil)
            ->exists();

        if (!$validOwner) {
            return back()->with('error', 'Nie masz uprawnień do ustalenia terminu.');
        }

        DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->update([
                'proponowany_termin' => $request->termin,
                'termin_zaakceptowany_wlasciciel' => 1,
                'termin_zaakceptowany_wykonawca' => 0
            ]);

        return back()->with('success', 'Termin został zaproponowany.');
    }

    public function changeTerminOwner(Request $request)
    {
        $request->validate([
            'id_zgloszenia' => 'required|integer',
            'termin' => 'required|date|after:now'
        ]);

        $idProfil = Auth::user()->id_profil;

        $validOwner = DB::table('zgloszenia')
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->where('zgloszenia.id_zgloszenia', $request->id_zgloszenia)
            ->where('oferty.id_profil_owner', $idProfil)
            ->exists();

        if (!$validOwner) {
            return back()->with('error', 'Nie masz uprawnień.');
        }

        DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->update([
                'proponowany_termin' => $request->termin,
                'termin_zaakceptowany_wlasciciel' => 1,
                'termin_zaakceptowany_wykonawca' => 0,
                'ostateczny_termin' => null
            ]);

        return back()->with('success', 'Termin został zmieniony.');
    }

    public function acceptTerminOwner(Request $request)
    {
        $request->validate([
            'id_zgloszenia' => 'required|integer'
        ]);

        $idProfil = Auth::user()->id_profil;

        $zgloszenie = DB::table('zgloszenia')
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->where('zgloszenia.id_zgloszenia', $request->id_zgloszenia)
            ->where('oferty.id_profil_owner', $idProfil)
            ->select('zgloszenia.*')
            ->first();

        if (!$zgloszenie) {
            return back()->with('error', 'Brak uprawnień.');
        }

        $updateData = [
            'termin_zaakceptowany_wlasciciel' => 1,
        ];

        // jeśli wykonawca też zaakceptował
        if ($zgloszenie->termin_zaakceptowany_wykonawca == 1) {
            $updateData['ostateczny_termin'] = $zgloszenie->proponowany_termin;
        }

        DB::table('zgloszenia')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->update($updateData);

        $workerUser = DB::table('users')
            ->where('id_profil', $zgloszenie->id_profil_wykonawca)
            ->first();

        if ($workerUser) {

            $dataFormatted = \Carbon\Carbon::parse(
                $zgloszenie->proponowany_termin
            )->format('Y-m-d H:i');

            DB::table('powiadomienia')->insert([
                'tytul' => 'Termin zaakceptowany',
                'text' => 'Gospodarz zaakceptował termin: ' . $dataFormatted,
                'odzcytane' => 0,
                'id_user' => $workerUser->id,
            ]);
        }

        return back()->with('success', 'Termin zaakceptowany!');
    }
}