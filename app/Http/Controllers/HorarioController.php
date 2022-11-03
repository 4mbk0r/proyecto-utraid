<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function show(int $horario)
    {
        //
        //return 'ss';
        $horarios =  DB::table('horarios')
        ->where('sala', '=', $horario)
        ->get();
        return $horarios;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Horario $horario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horario $horario)
    {
        //
    }
    public static function horario_disponible(Request $request)
    {
        //
        //return 'ss';

        /* falta modificar el horaraio  */

        
        try {
            $datos = $request['cita_nueva'];
            
            $consultorio  =  $datos['consultorio'];
            $fecha  =  $datos['fecha'];

        $horarios =  DB::table('horarios')
        ->leftJoin('agendas', function($join) use ($fecha)
        {
            $join->on('agendas.horario', '=', 'horarios.id_horario');
            $join->where('agendas.fecha', '=', $fecha);
            
        })
        ->whereNull('fecha')
        ->where('sala', '=', $consultorio)
        ->get();
        
        } catch (\Throwable $th) {
            return $th;
        }
        return $horarios;
    }
    
}






