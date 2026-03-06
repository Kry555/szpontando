<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OfertyAddController extends Controller
{
    public function show_form()
    {
        return view('add-ofert');
    }

    public function add_ofert(Request $request)
    {
        $request->validate([
            'adres' => 'required|string|max:255',
            'typ' => 'required|string|max:100',
            'cena' => 'required|numeric|min:0',
            'do_kiedy_wazne' => 'required|date|after:tomorrow',
            'opis' => 'required|string|max:1000',
        ]);

        $idprofil = Auth::user()->id_profil;
        DB::table('oferty')->insert([
            'id_profil_owner' => $idprofil,
            'adres' => $request->adres,
            'typ' => $request->typ,
            'cena' => $request->cena,
            'do_kiedy_wazne' => $request->do_kiedy_wazne,
            'opis' => $request->opis,
            'status' => 'aktywna',
        ]);

        return redirect()->route('main');
    }
}
