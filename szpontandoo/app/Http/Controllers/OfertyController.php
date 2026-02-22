<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OfertyController extends Controller
{
    public function show()
    {
        return view('main');
    }

    public function oferty()
    {
        if (!auth()->check()) {
            return view('main');
        }
        $id = auth()->user()->id_profil;
        //SELECT * FROM oferty WHERE  != 1;
        $dane = DB::table('oferty')->where('id_profil_owner', '!=', $id)->get();

        return $dane;
    }
}
