<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (!Auth::check() || Auth::user()->czy_admin != 1) {
                    return redirect('/')->with('error', 'Brak uprawnień administratora.');
                }
                return $next($request);
            }),
        ];
    }

    // Panel główny administratora
    public function dashboard()
    {
        $stats = [
            'nowe_zgloszenia' => DB::table('zgloszenia_naduzyc')->where('status', 'nowe')->count(),
            'niskie_oceny' => DB::table('profil')->where('ocena', '<', 2.5)->count(),
        ];
        
        // Pobranie statystyk ofert z ostatnich 7 dni (wykres)
        $ofertyData = DB::table('oferty')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $usersStats = DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return view('admin.dashboard', compact('stats', 'ofertyData', 'usersStats'));
    }

    // Banowanie ogłoszenia z widoku ogłoszenia
    public function banujOferte(Request $request)
    {
        $request->validate([
            'id_oferty' => 'required|integer|exists:oferty,id_oferty',
            'powod' => 'required|string|max:500'
        ]);

        $oferta = DB::table('oferty')->where('id_oferty', $request->id_oferty)->first();

        if ($oferta->status === 'zbanowana') {
            return back()->with('error', 'Ta oferta jest już zbanowana.');
        }

        DB::table('oferty')
            ->where('id_oferty', $request->id_oferty)
            ->update([
                'status' => 'zbanowana',
                'updated_at' => now()
            ]);

        // Powiadomienie właściciela
        DB::table('powiadomienia')->insert([
            'tytul' => 'Twoja oferta została zbanowana',
            'text' => 'Oferta: ' . $oferta->typ . ' została zablokowana. Powód: ' . $request->powod,
            'odzcytane' => 0,
            'id_user' => DB::table('users')->where('id_profil', $oferta->id_profil_owner)->value('id')
        ]);

        DB::table('admin_logs')->insert([
            'admin_id' => Auth::id(),
            'action' => 'Ban ogłoszenia',
            'details' => 'Zbanowano ofertę ID: ' . $request->id_oferty . '. Powód: ' . $request->powod,
        ]);

        return back()->with('success', 'Ogłoszenie zostało zbanowane.');
    }

    // Odbanowanie ogłoszenia
    public function odbanujOferte(Request $request)
    {
        $request->validate([
            'id_oferty' => 'required|integer|exists:oferty,id_oferty'
        ]);

        $oferta = DB::table('oferty')->where('id_oferty', $request->id_oferty)->first();

        if ($oferta->status !== 'zbanowana') {
            return back()->with('error', 'Ta oferta nie jest zbanowana.');
        }

        DB::table('oferty')
            ->where('id_oferty', $request->id_oferty)
            ->update([
                'status' => 'aktywna',
                'updated_at' => now()
            ]);

        DB::table('admin_logs')->insert([
            'admin_id' => Auth::id(),
            'action' => 'Odbanowanie ogłoszenia',
            'details' => 'Przywrócono ofertę ID: ' . $request->id_oferty,
        ]);

        return back()->with('success', 'Ogłoszenie zostało przywrócone.');
    }

    // Widok zbanowanych ofert
    public function zbanowaneOferty()
    {
        $oferty = DB::table('oferty')
            ->join('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil')
            ->where('oferty.status', 'zbanowana')
            ->select('oferty.*', 'profil.nick', 'profil.imie', 'profil.nazwisko', 'profil.profilowe', 'profil.miasto', 'profil.email_kontaktowy', 'profil.ocena')
            ->orderBy('oferty.updated_at', 'desc')
            ->get(); // Pobieramy oferty z podstawowymi danymi profilu właściciela

        // Dla każdej oferty, pobieramy ostatnie zlecenia właściciela (jako wykonawcy)
        foreach ($oferty as $o) {
            $o->ostatnie_zlecenia = DB::table('zgloszenia')
                ->join('oferty as zlecenia_oferty', 'zgloszenia.id_oferty', '=', 'zlecenia_oferty.id_oferty')
                ->leftJoin('oceny', function ($join) {
                    $join->on('oceny.id_zgloszenia', '=', 'zgloszenia.id_zgloszenia')
                        ->where('oceny.rola', '=', 'gospodarz'); // Opinia OD gospodarza DLA pracownika
                })
                ->leftJoin('profil as autor_opinii', 'oceny.id_profil_autor', '=', 'autor_opinii.id_profil')
                ->where('zgloszenia.id_profil_wykonawca', $o->id_profil_owner) // Szukamy zleceń, gdzie właściciel oferty był wykonawcą
                ->whereNotNull('zgloszenia.ostateczny_termin')
                ->orderBy('zgloszenia.ostateczny_termin', 'desc')
                ->limit(3)
                ->select(
                    'zlecenia_oferty.typ',
                    'zlecenia_oferty.adres',
                    'zlecenia_oferty.cena',
                    'zlecenia_oferty.do_kiedy_wazne',
                    'zlecenia_oferty.opis as oferta_opis',
                    'oceny.gwiazdki', 'oceny.opis as opinia_tekst', 'autor_opinii.nick as autor_nick', 'autor_opinii.profilowe as autor_foto'
                )
                ->get()->toJson(JSON_HEX_APOS | JSON_HEX_QUOT);
        }

        return view('admin.zbanowane', compact('oferty'));
    }

    // Widok z UŻ o niskich ocenach
    public function niskieOceny()
    {
        $uzytkownicy = DB::table('profil')
            ->join('users', 'profil.id_profil', '=', 'users.id_profil')
            ->where('profil.ocena', '<', 2.5) // Próg "niskiej oceny"
            ->select('users.*', 'profil.imie', 'profil.nazwisko', 'profil.miasto', 'profil.email_kontaktowy', 'profil.profilowe', 'profil.ocena')
            ->get();

        return view('admin.niskie_oceny', ['uzytkownicy' => $uzytkownicy]);
    }

    // Czasowy ban dla użytkownika
    public function banujUzytkownika(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'dni' => 'required|integer|min:1',
            'powod' => 'required|string|max:255'
        ]);

        // Blokada banowania adminów
        $uzytkownik = DB::table('users')->where('id', $request->id_user)->first();
        if ($uzytkownik && $uzytkownik->czy_admin) {
            return back()->with('error', 'Nie można zbanować administratora!');
        }

        // Uwaga: Zakładam istnienie kolumny 'zbanowany_do' w tabeli users
        DB::table('users')
            ->where('id', $request->id_user)
            ->update([
                'aktywny' => 0,
                'zbanowany_do' => now()->addDays((int) $request->dni),
                'powod_bana' => $request->powod
            ]);

        DB::table('admin_logs')->insert([
            'admin_id' => Auth::id(),
            'action' => 'Ban użytkownika',
            'details' => 'Użytkownik ID: ' . $request->id_user . ' zbanowany na ' . $request->dni . ' dni. Powód: ' . $request->powod,
        ]);

        return back()->with('success', 'Użytkownik został zbanowany na ' . $request->dni . ' dni.');
    }

    // Ręczne odbanowanie użytkownika przed czasem
    public function odbanujUzytkownika(Request $request)
    {
        $request->validate(['id_user' => 'required|integer']);

        DB::table('users')
            ->where('id', $request->id_user)
            ->update([
                'aktywny' => 1,
                'zbanowany_do' => null,
                'powod_bana' => null
            ]);

        DB::table('admin_logs')->insert([
            'admin_id' => Auth::id(),
            'action' => 'Odbanowanie użytkownika',
            'details' => 'Użytkownik ID: ' . $request->id_user . ' został ręcznie odbanowany.',
        ]);

        return back()->with('success', 'Użytkownik został odbanowany.');
    }

    // Widok zgłoszonych ogłoszeń
    public function zgloszoneOferty()
    {
        $zgloszenia = DB::table('zgloszenia_naduzyc')
            ->join('oferty', 'zgloszenia_naduzyc.id_oferty', '=', 'oferty.id_oferty')
            ->join('profil', 'oferty.id_profil_owner', '=', 'profil.id_profil')
            ->select('zgloszenia_naduzyc.*', 'oferty.opis', 'oferty.status as oferta_status', 'profil.nick', 'profil.imie', 'profil.nazwisko', 'profil.miasto', 'profil.email_kontaktowy', 'profil.profilowe', 'profil.ocena')
            ->where('zgloszenia_naduzyc.status', 'nowe')
            ->get();

        return view('admin.zgloszenia', ['zgloszenia' => $zgloszenia]);
    }

    // Rozpatrzenie zgłoszenia: ban / jest ok
    public function rozpatrzZgloszenie(Request $request)
    {
        $request->validate([
            'id_zgloszenia' => 'required|integer',
            'decyzja' => 'required|in:ban,ok'
        ]);

        $zgloszenie = DB::table('zgloszenia_naduzyc')->where('id_zgloszenia', $request->id_zgloszenia)->first();

        if ($request->decyzja == 'ban') {
            DB::table('oferty')->where('id_oferty', $zgloszenie->id_oferty)->update(['status' => 'zbanowana']);
            
            DB::table('admin_logs')->insert([
                'admin_id' => Auth::id(),
                'action' => 'Rozpatrzenie zgłoszenia - BAN',
                'details' => 'Zbanowano ofertę ID: ' . $zgloszenie->id_oferty . ' na podstawie zgłoszenia ID: ' . $request->id_zgloszenia,
            ]);
        }

        DB::table('zgloszenia_naduzyc')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->update(['status' => 'rozpatrzone']);

        return back()->with('success', 'Zgłoszenie zostało rozpatrzone.');
    }

    // Wyszukiwanie użytkownika i statystyki
    public function statystykiUzytkownika(Request $request)
    {
        $search = $request->input('search');
        $query = DB::table('users')
            ->leftJoin('profil', 'users.id_profil', '=', 'profil.id_profil')
            ->select('users.id as id', 'users.nick', 'users.email', 'users.aktywny', 'users.zbanowany_do', 'users.created_at', 'users.id_profil', 'profil.ocena', 'profil.profilowe', 'profil.imie', 'profil.nazwisko', 'profil.miasto', 'profil.email_kontaktowy');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('users.nick', 'LIKE', "%$search%")
                  ->orWhere('users.id', '=', is_numeric($search) ? $search : null);
            });
        }

        $allUsers = $query->get();

        foreach ($allUsers as $u) {
            $u->ostatnie_zlecenia = DB::table('zgloszenia')
                ->join('oferty', 'zgloszenia.id_oferty', '=', 'oferty.id_oferty')
                ->leftJoin('oceny', function ($join) {
                    $join->on('oceny.id_zgloszenia', '=', 'zgloszenia.id_zgloszenia')
                        ->where('oceny.rola', '=', 'gospodarz'); 
                })
                ->leftJoin('profil as autor_opinii', 'oceny.id_profil_autor', '=', 'autor_opinii.id_profil')
                ->where('zgloszenia.id_profil_wykonawca', '=', $u->id_profil)
                ->whereNotNull('zgloszenia.ostateczny_termin')
                ->orderBy('zgloszenia.ostateczny_termin', 'desc')
                ->limit(3)
                ->select('oferty.typ', 'oferty.adres', 'oferty.cena', 'oferty.do_kiedy_wazne', 'oferty.opis as oferta_opis', 'oceny.gwiazdki', 'oceny.opis as opinia_tekst', 'autor_opinii.nick as autor_nick', 'autor_opinii.profilowe as autor_foto')
                ->get()
                ->toJson(JSON_HEX_APOS | JSON_HEX_QUOT);
        }

        $user = $search ? $allUsers->first() : null;
        $iloscOfert = $user ? DB::table('oferty')->where('id_profil_owner', $user->id_profil)->count() : 0;
        $zaakceptowaneOferty = $user ? DB::table('oferty')->where('id_profil_owner', $user->id_profil)->where('status', 'zaakceptowana')->count() : 0;

        $userLogs = [];
        if ($user) {
            $userLogs = DB::table('admin_logs')
                ->join('users', 'admin_logs.admin_id', '=', 'users.id')
                ->where('admin_logs.details', 'LIKE', '%Użytkownik ID: ' . $user->id . ' %')
                ->select('admin_logs.*', 'users.nick as admin_nick')
                ->orderBy('admin_logs.created_at', 'desc')
                ->get();
        }

        return view('admin.user_stats', compact('allUsers', 'user', 'iloscOfert', 'zaakceptowaneOferty', 'userLogs'));
    }

public function dziennikZdarzen()
{
    $logi = DB::table('admin_logs')

        // admin wykonujący akcję
        ->join('users as admins', 'admin_logs.admin_id', '=', 'admins.id')
        ->leftJoin('profil as admin_profil', 'admins.id_profil', '=', 'admin_profil.id_profil')

        // wyciąganie ID użytkownika z tekstu loga
        ->leftJoin('users as target_user', DB::raw("
            CAST(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(admin_logs.details, 'Użytkownik ID: ', -1),
                    ' ',
                    1
                ) AS UNSIGNED
            )
        "), '=', 'target_user.id')

        ->leftJoin('profil as target_profil', 'target_user.id_profil', '=', 'target_profil.id_profil')

        ->select(

            'admin_logs.*',

            // admin
            'admins.nick as admin_nick',
            'admin_profil.imie as admin_imie',
            'admin_profil.nazwisko as admin_nazwisko',
            'admin_profil.miasto as admin_miasto',
            'admin_profil.profilowe as admin_profilowe',

            // target user
            'target_user.id as target_id',
            'target_user.nick as target_nick',
            'target_user.email',

            'target_profil.imie',
            'target_profil.nazwisko',
            'target_profil.miasto',
            'target_profil.profilowe',
            'target_profil.ocena'
        )

        ->orderBy('admin_logs.created_at', 'desc')
        ->get();

    return view('admin.logs', compact('logi'));
}
}