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
        return Inertia::render('Auth/Register', ['cargos' => $cargos]);
        return  inertia();
    }

}
