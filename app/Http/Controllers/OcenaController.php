<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OcenaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_zgloszenia' => 'required|integer',
            'id_profil_oceniany' => 'required|integer',
            'gwiazdki' => 'required|integer|min:0|max:5',
            'opis' => 'nullable|string|max:255',
            'rola' => 'required|in:pracownik,gospodarz',
        ]);

        $id_autor = Auth::user()->id_profil;

        // Sprawdź czy ocena już istnieje
        $exists = DB::table('oceny')
            ->where('id_zgloszenia', $request->id_zgloszenia)
            ->where('id_profil_autor', $id_autor)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Już wystawiłeś opinię do tego zlecenia.');
        }

        DB::table('oceny')->insert([
            'id_zgloszenia' => $request->id_zgloszenia,
            'id_profil_autor' => $id_autor,
            'id_profil_oceniany' => $request->id_profil_oceniany,
            'gwiazdki' => $request->gwiazdki,
            'opis' => $request->opis,
            'rola' => $request->rola,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Dziękujemy za wystawienie opinii!');
    }
}
