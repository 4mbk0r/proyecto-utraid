<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaBoletaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lugares = DB::table('institucions')
            ->select('*')
            ->get();
        return [
            'lugares'=> $lugares
        ];

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
        $cita = $request['cita'];
        //return $fecha['lugar'];
        $lugares = DB::table('institucions')
            ->select('*')
            ->get();
        $fichas = DB::table('fichas')
            ->select('*')
            ->where('fichas.institucion', '=', $cita['institucion'])
            ->where('fichas.fecha', '=', $cita['fecha'])
            ->get();
        if (count($fichas) > 0) {
            $horarios = DB::table('fichas')
                ->select('horarios.hora_inicio', 'horarios.id')
                ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                ->where('fichas.institucion', '=', $cita['institucion'])
                ->where('fichas.fecha', '=', $cita['fecha'])
                ->whereNull('dar_citas.id_persona')
                ->groupBy('horarios.hora_inicio', 'horarios.id')
                ->orderBy('horarios.hora_inicio')
                ->get();

            $respuesta = [
                'horarios' => $horarios,
                'lugares' => $lugares,
                'correcto' => true,
            ];
            return $respuesta;
        } else {
            
            $horarios = DB::table('calendariolineals')
                ->select('horarios.id', 'horarios.hora_inicio')
                ->leftJoin('configuracions', 'configuracions.id', '=', 'calendariolineals.id_configuracion')
                ->leftJoin('asignar_config_salas', 'asignar_config_salas.id_conf', '=', 'configuracions.id')
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'asignar_config_salas.id_conf_sala')
                ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
                ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horario')
                ->where('calendariolineals.fecha_inicio', '<=', $cita['fecha'])
                ->where( 'calendariolineals.fecha_final', '>', $cita['fecha'])
                ->where('configuracions.institucion', '=', $cita['institucion'])
                ->groupBy('horarios.id', 'horarios.hora_inicio')
                ->orderBy('horarios.hora_inicio')
                ->get();
            if(count($horarios) > 0){
                $respuesta = [
                    'horarios' => $horarios,
                    'lugares' => $lugares,
                    'correcto' => true,
                ];
                return $respuesta;       
            }
            $respuesta = [
                'horarios' => $horarios,
                'lugares' => $lugares,
                'correcto' => false,
            ];
            
            return $respuesta;
        }
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
