<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigGeneralController extends Controller
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
        $salas = DB::table('configuracions')
            ->leftJoin('asignar_config_salas', 'asignar_config_salas.id_conf', '=', 'configuracions.id')
            ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
            ->where('configuracions.id', $request['id'])
            ->select('salas.*')
            ->orderBy('salas.descripcion')
            ->get();
        $salas_disponibles = [];
        foreach ($salas as $key => $value) {
            # code...
            $x = DB::table('configuracions')
                ->leftJoin('asignar_config_salas', 'asignar_config_salas.id_conf', '=', 'configuracions.id')
                ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                ->leftJoin('conf_salas',    'conf_salas.id', '=', 'asignar_config_salas.id_conf_sala')
                ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
                ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horario')
                ->where('configuracions.id', '=', $request['id'])
                ->where('salas.id', '=', $value->id)
                ->select('*')
                ->get();
            array_push($salas_disponibles, $x);
        }

        $resp = [
            'salas' => $salas,
            'salas_disponibles' => $salas_disponibles

        ];
        return $resp;
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
    public static function crear_sala(Request $request)
    {
        //return $request;
        $nueva_sala = $request['nueva_sala'];
        $datos = $request['datos'];
        $horarios_nuevos = $request['horarios'];
        //return $request;
        $sala = DB::table('salas')
            ->where('salas.institucion', '=', $datos['institucion'])
            ->where('salas.descripcion', '=', $nueva_sala['descripcion'])
            ->first();
        if (empty($sala)) {
            $sala = DB::table('salas')
                ->insertGetId([
                    'descripcion' => $nueva_sala['descripcion'],
                    'institucion' => $datos['institucion']
                ]);
            $sala = DB::table('salas')
                ->where('salas.institucion', '=', $datos['institucion'])
                ->where('salas.descripcion', '=', $nueva_sala['descripcion'])
                ->first();
        }
        
        $config = DB::table('conf_salas')
            ->where('tiempo_apertura', '=', $nueva_sala['tiempo_apertura'])
            ->where('tiempo_cierre', '=', $nueva_sala['tiempo_cierre'])
            ->wherenull('tiempo_descanso')
            ->where('min_promedio_atencion', '=', $nueva_sala['min_promedio_atencion'])
            ->get();
        if (count($config) == 0) {
            DB::table('conf_salas')
                ->insertGetId([
                    'tiempo_apertura' => $nueva_sala['tiempo_apertura'],
                    'tiempo_cierre' => $nueva_sala['tiempo_cierre'],
                    'min_promedio_atencion' => $nueva_sala['min_promedio_atencion'],
                    //'institucion' => $datos['institucion']
                ]);
            $config = DB::table('conf_salas')
                ->where('tiempo_apertura', '=', $nueva_sala['tiempo_apertura'])
                ->where('tiempo_cierre', '=', $nueva_sala['tiempo_cierre'])
                ->wherenull('tiempo_descanso')
                ->where('min_promedio_atencion', '=', $nueva_sala['min_promedio_atencion'])
                ->first();
        } else {
            $config = $config[0];
        }
        $horarios = DB::table('conf_salas')
            ->select('horarios.*')
            ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
            ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horario')
            ->where('conf_salas.id', '=', $config->id)
            ->whereNotNull('horarios.id')
            ->get();
        $valor = [];
        if (count($horarios) === 0) {
            foreach ($horarios_nuevos as $key => $value) {
                # code...
                $r = [
                    'hora_inicio' => $value['hora_inicio'],
                    'hora_final' => $value['hora_final']
                ];
                $x = DB::table('horarios')
                    ->where('hora_inicio', '=', $value['hora_inicio'])
                    ->where('hora_final', '=', $value['hora_final'])
                    ->first();
                if (empty($x)) {
                    $x = DB::table('horarios')
                        ->insertGetId($r);
                } else {
                    $x = $x->id;
                }
                $x = DB::table('asignar_horarios')
                    ->insert([
                        'id_horario' => $x,
                        'id_conf_sala' => $config->id
                    ]);
            }
        }
        $x = DB::table('asignar_config_salas')
            ->where('id_conf_sala', '=', $config->id)
            ->where('id_sala', '=', $sala->id)
            ->where('id_conf', '=', $datos['id_configuracion'])
            ->exists();
        if (!$x) {
            DB::table('asignar_config_salas')
                ->insert([
                    'id_conf_sala' => $config->id,
                    'id_sala' => $sala->id,
                    'id_conf' => $datos['id_configuracion']
                ]);
        }
        return $horarios;
    }
    public static function configuracion(Request $request)
    {
        //return $request;
        //return $request;
        $salas =  $request['salas'];
        $config = $request['config'];
        /*$horario = DB::table('fichas')->select('*')
            ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->leftJoin('conf_salas', 'conf_salas.id', '=', 'fichas.id_conf_sala')
            ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
            ->where('fichas.fecha', $datos['fecha'])
            ->where('fichas.id_sala', $datos['id_sala'])
            ->orderBy('horarios.hora_inicio')
            ->get();
        //return $horario;
        //return $datos['fecha'];
        /*$is_calendar =  $equipos = DB::table('calendarios')
            ->where('fecha', '=', $datos['fecha'])
            ->where('atencion', '=', 'atencion')
            ->get();*/
        $query = DB::table('configuracions')
            ->select('*')
            ->leftJoin('asignar_config_salas', 'asignar_config_salas.id_conf', '=', 'configuracions.id')
            ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
            ->leftJoin('conf_salas', 'conf_salas.id', '=', 'asignar_config_salas.id_conf_sala')
            ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
            ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horario')
            ->where('configuracions.id', '=', $config['id'])
            ->where('salas.id', '=', $salas['id'])
            ->get();

        return $query;
    }
    public static function cambiarhorariogeneral(Request $request)
    {
        ///return $request;
        
        $datos_sala = $request['nueva_sala'];
        $datos = $request['datos'];
        $horarios_nuevos = $request['horarios'];
        $nueva_sala = $request['config_nueva'];
        //return $nueva_sala;
        $conf_antigua = DB::table('configuracions')
            ->select('conf_salas.*')
            ->leftJoin('asignar_config_salas', 'asignar_config_salas.id_conf', '=', 'configuracions.id')
            ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
            ->leftJoin('conf_salas', 'conf_salas.id', '=', 'asignar_config_salas.id_conf_sala')
            //->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
            //->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horario')
            ->where('configuracions.id', '=', $datos['id'])
            ->where('salas.id', '=', $datos_sala['id'])
            ->first();
        $config = DB::table('conf_salas')
            ->where('tiempo_apertura', '=', $nueva_sala['tiempo_apertura'])
            ->where('tiempo_cierre', '=', $nueva_sala['tiempo_cierre'])
            ->whereNull('tiempo_descanso')
            ->where('min_promedio_atencion', '=', $nueva_sala['min_promedio_atencion'])
            ->get();
        if (count($config) == 0) {
            DB::table('conf_salas')
                ->insertGetId([
                    'tiempo_apertura' => $nueva_sala['tiempo_apertura'],
                    'tiempo_cierre' => $nueva_sala['tiempo_cierre'],
                    'min_promedio_atencion' => $nueva_sala['min_promedio_atencion'],
                    //'institucion' => $datos['institucion']
                ]);
            $config = DB::table('conf_salas')
                ->where('tiempo_apertura', '=', $nueva_sala['tiempo_apertura'])
                ->where('tiempo_cierre', '=', $nueva_sala['tiempo_cierre'])
                ->where('min_promedio_atencion', '=', $nueva_sala['min_promedio_atencion'])
                ->whereNull('tiempo_descanso')
                
                ->first();
        } else {
            $config = $config[0];
        }
        $horarios = DB::table('conf_salas')
            ->select('horarios.*')
            ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
            ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horario')
            ->where('conf_salas.id', '=', $config->id)
            ->whereNotNull('horarios.id')
            ->get();
        $valor = [];
        if (count($horarios) === 0) {
            //return $horarios_nuevos;
            foreach ($horarios_nuevos as $key => $value) {
                # code...   
                $r = [
                    'hora_inicio' => $value['hora_inicio'],
                    'hora_final' => $value['hora_final']
                ];
                $x = DB::table('horarios')
                    ->where('hora_inicio', '=', $value['hora_inicio'])
                    ->where('hora_final', '=', $value['hora_final'])
                    ->first();
                if (empty($x)) {
                    $x = DB::table('horarios')
                        ->insertGetId($r);
                } else {
                    $x = $x->id;
                }
                $x = DB::table('asignar_horarios')
                    ->insert([
                        'id_horario' => $x,
                        'id_conf_sala' => $config->id
                    ]);
            }
            
        }
        //return $conf_antigua;
        $x = DB::table('asignar_config_salas')
            ->where('id_conf_sala', '=', $conf_antigua->id)
            ->where('id_sala', '=', $datos_sala['id'])
            ->where('id_conf', '=', $datos['id_configuracion'])
            ->update([
                'id_conf_sala' => $config->id
            ]);
        
            
        return $request;        
    }
    public static function eliminarhorariogeneral(Request $request)
    {
        //return $request;
        
        $sala = $request['nueva_sala'];
        $config = $request['datos'];
        //$horarios_nuevos = $request['horarios'];
        //$nueva_sala = $request['config_nueva'];
        
        $query = DB::table('asignar_config_salas')
            ->select('*')
            ->where('asignar_config_salas.id_conf', '=', $config['id'])
            ->where('asignar_config_salas.id_sala', '=', $sala['id'])
            ->delete();

        return $request;        
    }
}
