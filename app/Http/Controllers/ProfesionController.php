<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfesionController extends Controller
{
    //
    public static function listar_profesion(Request $request)
    {
        /*
        select cargo from users
        WHERE cargo != 'Admin'
        GROUP by cargo
        */
        try {
            $lista = DB::table('users')
            ->select('cargo')
            ->where('cargo', '!=', 'Admin')
            ->groupBy('cargo')
            ->get();
        } catch (\Throwable $th) {
            return $th;
            //new Response(['message' => 'th'], 400);
        }
        return $lista;
    }
}
