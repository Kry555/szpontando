<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class OfertyAddControler extends Controller
{
    public function show_form()
    {
        return view('dodawanie_ofert');
    }

    public function add_ofert(Request $request)
    {
        //
    }
}
