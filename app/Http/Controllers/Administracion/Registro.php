<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class Registro extends Controller
{
    //
    public function index()
    {
        //

        
        $cargos = DB::table('cargos')->select('*')->get();
        $establecimiento = DB::table('establecimientos')->select('*')->get();
        
        return Inertia::render('Registro', ['establecimiento' => $establecimiento,
        'cargos' => $cargos,
        ]);
        return  inertia();
    }

}
