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

        return view('admin.dashboard', compact('stats', 'ofertyData'));
    }

    // Banowanie ogłoszenia z widoku ogłoszenia
    public function banujOferte(Request $request)
    {
        $request->validate(['id_oferty' => 'required|integer']);

        DB::table('oferty')
            ->where('id_oferty', $request->id_oferty)
            ->update(['status' => 'zbanowana']);

        DB::table('admin_logs')->insert([
            'admin_id' => Auth::id(),
            'action' => 'Ban ogłoszenia',
            'details' => 'Zbanowano ofertę o ID: ' . $request->id_oferty,
        ]);

        return back()->with('success', 'Ogłoszenie zostało zbanowane.');
    }

    // Widok z UŻ o niskich ocenach
    public function niskieOceny()
    {
        $uzytkownicy = DB::table('profil')
            ->join('users', 'profil.id_profil', '=', 'users.id_profil')
            ->where('profil.ocena', '<', 2.5) // Próg "niskiej oceny"
            ->select('users.id', 'users.nick', 'profil.ocena', 'users.aktywny')
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
            ->select('zgloszenia_naduzyc.*', 'oferty.opis', 'oferty.status as oferta_status')
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
        $user = DB::table('users')
            ->join('profil', 'users.id_profil', '=', 'profil.id_profil')
            ->where('users.nick', 'LIKE', "%$search%")
            ->orWhere('users.id', $search)
            ->select('users.*', 'profil.ocena')
            ->first();

        $iloscOfert = $user ? DB::table('oferty')->where('id_profil_owner', $user->id_profil)->count() : 0;
        $zaakceptowaneOferty = $user ? DB::table('oferty')->where('id_profil_owner', $user->id_profil)->where('status', 'zaakceptowana')->count() : 0;

        $userLogs = [];
        if ($user) {
            $userLogs = DB::table('admin_logs')
                ->join('users', 'admin_logs.admin_id', '=', 'users.id')
                ->where('admin_logs.details', 'LIKE', '%ID: ' . $user->id . '%')
                ->select('admin_logs.*', 'users.nick as admin_nick')
                ->orderBy('admin_logs.created_at', 'desc')
                ->get();
        }

        return view('admin.user_stats', compact('user', 'iloscOfert', 'zaakceptowaneOferty', 'userLogs'));
    }

    public function dziennikZdarzen()
    {
        $logi = DB::table('admin_logs')
            ->join('users', 'admin_logs.admin_id', '=', 'users.id')
            ->select('admin_logs.*', 'users.nick as admin_nick')
            ->orderBy('admin_logs.created_at', 'desc')
            ->get();

        return view('admin.logs', compact('logi'));
    }
}