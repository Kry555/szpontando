<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- pamiętaj o imporcie

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
            'zdjecie_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'zdjecie_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $idprofil = Auth::user()->id_profil;

        $data = [
            'id_profil_owner' => $idprofil,
            'adres' => $request->adres,
            'typ' => $request->typ,
            'cena' => $request->cena,
            'do_kiedy_wazne' => $request->do_kiedy_wazne,
            'opis' => $request->opis,
            'status' => 'aktywna',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Obsługa zdjęć - zapisujemy w public/images/oferty
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

        return redirect()->route('main');
    }
}
