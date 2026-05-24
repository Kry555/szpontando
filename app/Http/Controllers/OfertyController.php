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
                'oferty.created_at',
                'oferty.status',
                'oferty.zdjecie_1',
                'oferty.zdjecie_2'
            )->leftJoin('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil');

            //  FILTER
            if ($request->filled('typ')) {
                $query->where('oferty.typ', $request->typ);
            }
            if ($request->filled('cena_min')) {
                $query->where('oferty.cena', '>=', $request->cena_min);
            }

            if ($request->filled('cena_max')) {
                $query->where('oferty.cena', '<=', $request->cena_max);
            }
            if ($request->filled('miasto')) {
                $query->where('oferty.adres', 'LIKE', $request->miasto . '%');
            }
if ($request->filled('status')) {
    $query->where('oferty.status', $request->status);
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
            'oferty.created_at',
            'oferty.status',
            'oferty.zdjecie_1',
            'oferty.zdjecie_2'
        )->leftJoin('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil')
            ->where('oferty.id_profil_owner', '!=', $id);

        //  FILTER
        if ($request->filled('cena_min')) {
            $query->where('oferty.cena', '>=', $request->cena_min);
        }
        if ($request->filled('cena_max')) {
            $query->where('oferty.cena', '<=', $request->cena_max);
        }
        if ($request->filled('typ')) {
            $query->where('oferty.typ', $request->typ);
        }
        if ($request->filled('miasto')) {
            $query->where('oferty.adres', 'LIKE', $request->miasto . '%');
        }
if ($request->filled('status')) {
    $query->where('oferty.status', $request->status);
}
        //tutaj
        function miasta(Request $request)
        {
            $search = $request->get('q');

            $miasta = DB::table('oferty')
                ->where('adres', 'LIKE', $search . '%')
                ->distinct()
                ->orderBy('adres')
                ->limit(10)
                ->pluck('adres');

            return response()->json($miasta);
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

    public function storeOfert(Request $request)
    {
        $request->validate([
            'typ' => 'required|string',
            'adres' => 'required|string|max:255',
            'cena' => 'required|numeric|min:0',
            'do_kiedy_wazne' => 'required|date|after:now',
            'opis' => 'required|string',
            'zdjecie_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'zdjecie_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'id_profil_owner' => Auth::user()->id_profil,
            'typ' => $request->typ,
            'adres' => $request->adres,
            'cena' => $request->cena,
            'do_kiedy_wazne' => $request->do_kiedy_wazne,
            'opis' => $request->opis,
            'status' => 'aktywna',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($request->hasFile('zdjecie_1')) {
            $file = $request->file('zdjecie_1');
            $filename = time() . '_o1.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/oferty'), $filename);
            $data['zdjecie_1'] = $filename;
        }

        if ($request->hasFile('zdjecie_2')) {
            $file = $request->file('zdjecie_2');
            $filename = time() . '_o2.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/oferty'), $filename);
            $data['zdjecie_2'] = $filename;
        }

        DB::table('oferty')->insert($data);

        return redirect()->route('main')->with('success', 'Oferta została dodana pomyślnie!');
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
        } else {
            return back()->with('error', 'Nie możesz zgłosić się do własnej oferty.');
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

    public function ranking()
    {
        // Pobieramy profile, które mają jakąkolwiek ocenę, sortując od najwyższej
        $wykonawcy = DB::table('profil')
            ->whereNotNull('ocena')
            ->orderBy('ocena', 'desc')
            ->get();

        return view('ranking', ['wykonawcy' => $wykonawcy]);
    }

    public function zglosOferte(Request $request)
    {
        // Użytkownik musi być zalogowany
        if (!Auth::check()) {
            return back()->with('error', 'Musisz być zalogowany, aby zgłosić ofertę.');
        }

        $request->validate([
            'id_oferty' => 'required|integer|exists:oferty,id_oferty',
            'powod' => 'required|string|max:500'
        ]);

        $id_zgloszenia = DB::table('zgloszenia_naduzyc')->insertGetId([
            'id_oferty' => $request->id_oferty,
            'id_user_zgloszajacy' => Auth::id(),
            'powod' => $request->powod,
            'status' => 'nowe',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Oferta została zgłoszona do moderatora.');
    }

    public function pokazOferte($id)
    {
        // Pobieramy dane oferty i profil właściciela
        $oferta = DB::table('oferty')
            ->join('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil')
            ->where('oferty.id_oferty', $id)
            ->select(
                'oferty.*',
                'profil.nick as owner_nick',
                'profil.imie as owner_imie',
                'profil.nazwisko as owner_nazwisko',
                'profil.miasto as owner_miasto',
                'profil.email_kontaktowy as owner_email',
                'profil.ocena as owner_ocena',
                'profil.profilowe as owner_foto',
                'profil.sex as owner_sex'
            )
            ->first();

        if (!$oferta) {
            return redirect()->route('main')->with('error', 'Oferta nie istnieje.');
        }

        $ownerIdProfil = $oferta->id_profil_owner; // Pobieramy ID profilu właściciela

        // Pobieramy oferty stworzone przez tego właściciela
        $ownerCreatedOffers = DB::table('oferty')
            ->where('id_profil_owner', $ownerIdProfil)
            ->orderBy('created_at', 'desc')
            ->limit(5) // Ograniczamy do kilku ostatnich ofert
            ->select(
                'oferty.typ',
                'oferty.adres',
                'oferty.cena',
                'oferty.do_kiedy_wazne',
                'oferty.opis as oferta_opis' // Alias, aby pasowało do struktury z completed jobs
            )
            ->get()
            ->toJson(JSON_HEX_APOS | JSON_HEX_QUOT); // Kodujemy do JSON dla łatwego przekazania do JS

        // Pobieramy zlecenia wykonane przez tego właściciela jako wykonawcę
        $ownerCompletedJobs = DB::table('zgloszenia')
            ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
            ->leftJoin('oceny', function ($join) {
                $join->on('oceny.id_zgloszenia', '=', 'zgloszenia.id_zgloszenia')
                    ->where('oceny.rola', '=', 'gospodarz'); // Opinia OD gospodarza DLA pracownika
            })
            ->leftJoin('profil as autor_opinii', 'oceny.id_profil_autor', '=', 'autor_opinii.id_profil')
            ->where('zgloszenia.id_profil_wykonawca', $ownerIdProfil)
            ->whereNotNull('zgloszenia.ostateczny_termin')
            ->orderBy('zgloszenia.ostateczny_termin', 'desc')
            ->limit(5) // Ograniczamy do kilku ostatnich wykonanych zleceń
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
            ->toJson(JSON_HEX_APOS | JSON_HEX_QUOT); // Kodujemy do JSON dla łatwego przekazania do JS

        // Sprawdzamy, czy zalogowany użytkownik już wysłał zgłoszenie do tej oferty
        $juz_zgloszony = false;
        if (Auth::check()) {
            $juz_zgloszony = DB::table('zgloszenia')
                ->where('id_oferty', $id)
                ->where('id_profil_wykonawca', Auth::user()->id_profil)
                ->exists();
        }

        // Pobieramy listę osób, które się zgłosiły do tej konkretnej oferty
        $zgloszenia = DB::table('zgloszenia')
            ->join('profil', 'zgloszenia.id_profil_wykonawca', '=', 'profil.id_profil')
            ->where('zgloszenia.id_oferty', $id)
            ->select(
                'zgloszenia.*',
                'profil.nick',
                'profil.imie',
                'profil.nazwisko',
                'profil.profilowe',
                'profil.ocena',
                'profil.miasto'
            )
            ->get();

        return view('oferta_details', compact('oferta', 'zgloszenia', 'juz_zgloszony', 'ownerCreatedOffers', 'ownerCompletedJobs'));
    }

    /**
     * Wysyła wiadomość do innego użytkownika.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_id_profil' => 'required|integer|exists:profil,id_profil',
            'message_content' => 'required|string|max:1000',
        ]);

        $senderIdProfil = Auth::user()->id_profil;
        $recipientIdProfil = $request->recipient_id_profil;
        $messageContent = $request->message_content;

        // Zabezpieczenie przed wysłaniem wiadomości do samego siebie
        if ($senderIdProfil == $recipientIdProfil) {
            return back()->with('error', 'Nie możesz wysłać wiadomości do samego siebie.');
        }

        // Na potrzeby tego zadania, wiadomość zostanie zapisana jako powiadomienie.
        DB::table('powiadomienia')->insert([
            'tytul' => 'Nowa wiadomość od ' . Auth::user()->nick,
            'text' => $messageContent,
            'odzcytane' => 0,
            'id_user' => DB::table('users')->where('id_profil', $recipientIdProfil)->value('id'), // Pobieramy ID użytkownika z tabeli 'users' na podstawie 'id_profil'
        ]);
        return back()->with('success', 'Wiadomość została wysłana!');
    }
}
