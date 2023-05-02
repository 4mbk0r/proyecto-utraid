<?php

namespace App\Http\Controllers;

use App\Models\Boleta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoletaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $sw = DB::table('boletas')
        ->select('*')
        ->get();
        
        if (count($sw)> 0) {
            return $sw;
        }
        $a = [];
        return json_encode($a);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $s = (array)$request['datos'];
        
        $r = DB::table('boletas')
        ->where('id_institucion','=',$s['id_institucion'])
        ->get();
        if(count($r)==0){
            DB::table('boletas')->insert($s);
        }else{
            DB::table('boletas')
            ->where('id_institucion','=',$s['id_institucion'])
            ->update($s);
        }
        return DB::table('boletas')
        ->where('id_institucion','=',$s['id_institucion'])
        ->first();
        //DB::table('boletas')->updateOrInsert($s);   
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Boleta  $boleta
     * @return \Illuminate\Http\Response
     */
    public function show(String $boleta)
    {
        //
        $sw = DB::table('boletas')
        ->select('*')
        ->where('id_institucion', '=', $boleta)
        ->get();
        
        if (count($sw)> 0) {
            return $sw;
        }
        $emptyArray = [];

        return json_encode($emptyArray);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Boleta  $boleta
     * @return \Illuminate\Http\Response
     */
    public function edit(Boleta $boleta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Boleta  $boleta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Boleta $boleta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Boleta  $boleta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Boleta $boleta)
    {
        //
    }
}
