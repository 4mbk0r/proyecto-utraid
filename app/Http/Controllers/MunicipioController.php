<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\String_;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return 'index';
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
        $datos = $request['datos'];  
        $municipio = $request['municipio'];  
        $query = DB::table('viajes')
        ->insert([
            'id_municipio'=>$municipio,
            'id_sala' => $datos['id_sala'],
            'fecha' => $datos['fecha']
        ]);
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function show(string $municipio)
    {
        //
        $datos = json_decode($municipio);
        //return $datos;
        $is_calendar = DB::table('calendarios')
            ->where('fecha', '=', $datos->fecha)
            ->where('atencion', '=', 'atencion')
            ->get();
        if (count($is_calendar) == 0) {
            $municipio = DB::table('viajes')
            ->leftJoin('municipios', 'municipios.id', '=', 'viajes.id_municipio')
            ->where('fecha', '=', $datos->fecha)
            ->where('id_sala', '=',  $datos->id_sala)
            
            //->where('atencion', '=', 'atencion')
            ->get();
            $municipios = [];
            if( count($municipio) == 0){
                $municipios =   DB::table('municipios')
                ->select('*')
                ->get();
            }else{
                array_push($municipios, $municipio);
                    
            }

            return [
                'municipio' =>  $municipio,
                'municipios' => $municipios
            ];
        } else {
            $municipios =   DB::table('municipios')
            ->select('*')
            ->get();
            return [
                'municipio' =>  '',
                'municipios' => $municipios
            ];
        }
        return $is_calendar;

        
        return $municipio;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function edit(Municipio $municipio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Municipio $municipio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Municipio  $municipio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Municipio $municipio)
    {
        //
    }
}
