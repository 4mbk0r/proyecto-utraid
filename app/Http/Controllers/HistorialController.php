<?php

namespace App\Http\Controllers;

use App\Models\historial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistorialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //


        $table = DB::table("calendariolineals")
            ->select("calendariolineals.id as ide", "calendariolineals.fecha_final as col1");

        $sql = DB::table("calendariolineals")
            ->select("calendariolineals.id as ide", "calendariolineals.fecha_inicio as col1")
            ->union($table)
            ->toSql();
        
        return DB::table('calendariolineals')
            ->select('*')
            ->leftJoinSub($sql, 'Ts', function ($join) {
                $join->on('Ts.ide', '=', 'calendariolineals.id');
            })
            ->get();

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
        $r = $request['fechas'];
        $q = DB::table('calendarios')
        ->where('calendarios.fecha', '>=', $r['fecha_inicio'])
        ->where('calendarios.fecha', '<=', $r['fecha_final'])
        ->get();
        return $q;
        /*
        $fecha_inicio =  $request['fechas'][0];
        $fecha_final =  $request['fechas'][1];
        $datos = [
            'fecha_inicio' => $fecha_inicio,
            'fecha_final' => $fecha_final,
            'historial' => '0',
            'id_configuracion' => 1,
            'principal' => true
        ];
        DB::table('calendariolineals')->insertGetId($datos);
        $table = DB::table("calendariolineals")
            ->select("calendariolineals.id as ide", "calendariolineals.fecha_final as fecha_orden");

        $sql = DB::table("calendariolineals")
            ->select("calendariolineals.id as ide", "calendariolineals.fecha_inicio as fecha_orden")
            ->union($table)
            ->toSql();
        
        return DB::table('calendariolineals')
            ->select('*')
            ->leftJoinSub($sql, 'Ts', function ($join) {
                $join->on('Ts.ide', '=', 'calendariolineals.id');
            })
            ->orderBy('fecha_orden')
            ->get();

        return DB::table('calendariolineals')->select('*')->get();
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\historial  $historial
     * @return \Illuminate\Http\Response
     */
    public function show(historial $historial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\historial  $historial
     * @return \Illuminate\Http\Response
     */
    public function edit(historial $historial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\historial  $historial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, historial $historial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\historial  $historial
     * @return \Illuminate\Http\Response
     */
    public function destroy(historial $historial)
    {
        //
    }
}
