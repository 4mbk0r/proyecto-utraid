<?php

namespace App\Http\Controllers;

use App\Models\dar_cita;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DarCitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        /*
        select *
        from fichas
        left join dar_citas ON dar_citas.id_ficha = fichas.id
        left join  personas ON personas.id = dar_citas.id_persona          
         */

        /*
        $cargo = DB::table('fichas')
            ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
            ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')


            //->where('cargos.servicio', '=', 'true')
            //->where('cargo', '=','recepcionista')
            ->get();
        return $cargo;*/
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
        $fecha = $request['fecha'];
        $cita =  $request['cita'];
        $persona =  $request['paciente'];


        $validar =  DB::table('fichas')
            ->select('*')
            ->where('fecha', '=', $fecha)
            ->get();
        if (count($validar) == 0) {
            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get()->first();
            $salas = DB::table('asignar_config_salas')
                ->select('*')
                ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'asignar_config_salas.id_conf_sala')
                ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
                ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horario')
                ->where('id_conf', '=', $list_config->id_configuracion)
                ->get();

            foreach ($salas as $key => $value) {
                # code...
                $nuevo = [
                    'id_sala' => $value->id_sala,
                    'id_horario' => $value->id_horario,
                    'id_conf_sala' => $value->id_conf_sala,
                    'fecha' => $fecha,
                    'codigo' =>  $value->institucion,

                ];
                try {
                    DB::table('fichas')->insert($nuevo);
                } catch (\Throwable $th) {
                    return $th;
                }
            }
        }
        $validar =  DB::table('fichas')
            ->where('id_sala', '=', $cita['id_sala'])
            ->where('id_horario', '=', $cita['id_horario'])
            ->where('id_conf_sala', '=', $cita['id_conf_sala'])
            ->where('fecha', '=', $fecha)
            ->where('codigo', '=',  $cita['institucion'])
            ->where('fecha', '=', $fecha)
            ->first();

        try {
            $respuesta = DB::table('dar_citas')->insert(['id_ficha' => $validar->id, 'id_persona' => $persona['id']]);
        } catch (\Throwable $th) {
            return $th;
        }


        return $validar;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\dar_cita  $dar_cita
     * @return \Illuminate\Http\Response
     */
    public function show(string $fecha)
    {
        //
        //$fecha = $request['fecha'];
        $validar =  DB::table('fichas')
            ->select('id_sala', 'salas.descripcion')
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->where('fecha', '=', $fecha)
            ->groupBy('id_sala', 'salas.descripcion')
            ->get();
        if (count($validar) == 0) {
            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get()[0];
            $salas = DB::table('asignar_config_salas')
                ->select('*')
                ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'asignar_config_salas.id_conf_sala')
                ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
                ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horarios')
                ->where('id_conf', '=', $list_config->id_configuracion)
                ->get();

            foreach ($salas as $key => $value) {
                # code...
                $nuevo = [
                    'id_sala' => $value->id_sala,
                    'id_horario' => $value->id_horarios,
                    'id_conf_sala' => $value->id_conf_sala,
                    'fecha' => $fecha,
                    'codigo' =>  $value->institucion,

                ];
                DB::table('fichas')->insert($nuevo);
            }
        }
        $horarios =  [];
        foreach ($validar as $key => $value) {
            # code...
            $horario = DB::table('fichas')
                ->select('*')
                ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'fichas.id_sala')
                ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
                ->where('fecha', '=', $fecha)
                ->where('fichas.id_sala', '=', $value->id_sala)
                //->groupBy('id_sala', 'salas.descripcion')
                ->get();
            array_push($horarios, (array) $horario);
        }
        return $horarios;
    }

    /**
     * Show the form    for editing the specified resource.
     *
     * @param  \App\Models\dar_cita  $dar_cita
     * @return \Illuminate\Http\Response
     */
    public function edit(dar_cita $dar_cita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\dar_cita  $dar_cita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, dar_cita $dar_cita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\dar_cita  $dar_cita
     * @return \Illuminate\Http\Response
     */
    public function destroy(dar_cita $dar_cita)
    {
        //
    }
}
