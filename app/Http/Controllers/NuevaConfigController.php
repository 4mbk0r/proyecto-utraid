<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NuevaConfigController extends Controller
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
        //return $request;
        $fechas = $request['fecha_nueva'];
        $salas = $request['salas'];
        foreach ($fechas as $k => $fecha) {
            
            foreach ($salas as $key => $value) {
                # code...
                $s = DB::table("salas")
                    ->leftJoin("asignar_salas", function ($join) {
                        $join->on("asignar_salas.id_sala", "=", "salas.id");
                    })
                    ->leftJoin("conf_salas", function ($join) {
                        $join->on("conf_salas.id", "=", "asignar_salas.id_conf_sala");
                    })
                    ->leftJoin("asignar_horarios", function ($join) {
                        $join->on("asignar_horarios.id_conf_sala", "=", "conf_salas.id");
                    })
                    ->leftJoin("horarios", function ($join) {
                        $join->on("horarios.id", "=", "asignar_horarios.id_horario");
                    })
                    ->where("salas.id", "=", $value['id_sala'])
                    ->where("conf_salas.id", "=", $value['id_conf_sala'])
                    ->get();
                //return $s;
                foreach ($s as $i => $val) {
                    # code...
                    $nuevo = [
                        'id_sala' => $val->id_sala,
                        'id_horario' => $val->id_horario,
                        'id_conf_sala' => $val->id_conf_sala,
                        'fecha' => $fecha,
                        'institucion' =>  '01',
    
                    ];
                }
                DB::table('calendarios')->insert($fecha);
                    
                
                try {
                    DB::table('fichas')->insert($nuevo);
                } catch (\Throwable $th) {
                    return $th;
                }
            }
        }




        return $request;
        $fecha = $request['fecha'];
        $cita =  $request['cita'];
        //return $cita;
        $persona =  $request['paciente'];

        //verificamos que no tenemos un fecha 
        $validar =  DB::table('calendarios')
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
                ->orderBy('salas.descripcion')
                ->get();
            $i = [
                'fecha' => $fecha,
            ];
            DB::table('calendarios')->insert($i);
            foreach ($salas as $key => $value) {
                # code...
                $nuevo = [
                    'id_sala' => $value->id_sala,
                    'id_horario' => $value->id_horario,
                    'id_conf_sala' => $value->id_conf_sala,
                    'fecha' => $fecha,
                    'institucion' =>  $value->institucion,

                ];
                try {
                    DB::table('fichas')->insert($nuevo);
                } catch (\Throwable $th) {
                    return $th;
                }
            }
            /*
            select * from designar_equipo_lineals
            where id_conf

            */
            $e = DB::table('designar_equipo_lineals')
                ->select('*')
                ->where('id_conf', '=', $list_config->id_configuracion)
                ->get();
            foreach ($e as $key => $value) {
                # code...
                $nuevo = [
                    'fecha' => $fecha,
                    'id_equipo' => $value->id_equipo,
                    'id_sala' => $value->id_sala
                ];
                DB::table('designar_equipos')->insert($nuevo);
            }
        }
        $validar =  DB::table('fichas')
            ->where('id_sala', '=', $cita['id_sala'])

            ->where('id_horario', '=', $cita['id_horario'])
            ->where('id_conf_sala', '=', $cita['id_conf_sala'])
            ->where('fecha', '=', $fecha)
            ->where('institucion', '=',  $cita['institucion'])
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
