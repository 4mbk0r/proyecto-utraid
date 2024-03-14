<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TablaAgenda extends Controller
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

        //$request = json_decode(json_encode($request), true);
        //
        $fecha = $request['fecha'];
        $institucion =  $request['lugar'];
        
        if (empty($fecha) || empty($institucion)) {
            $respuesta = [];
            return $respuesta;
        }
        
        $results = DB::table(DB::raw("(select  CASE WHEN to_char(fecha, 'day') = 'saturday' THEN  DATE(fecha::date + INTERVAL '1 days') WHEN TRIM(to_char(fecha, 'day')) = 'sunday' THEN  DATE(fecha::date + INTERVAL '2 days')
            ELSE fecha END as fechaf
            from calendarios
            where atencion = 'feriado' 
            and TRIM(to_char(fecha, 'yyyy')) = TRIM(to_char('" . $fecha . "'::date, 'yyyy'))  ) AS subquery"))
            ->where('subquery.fechaf', '=', $fecha)
            ->get();
        if (count($results) > 0) {
            $resp = [
                'salas' => [],
                'salas_diponibles' => [],
                'equipo' => [],
                //'lista_conf' => $list_config,
                //'casa0' => $list_config[0]->id_configuracion
            ];
            return $resp;
        }
        $lugar = DB::table('institucions')
            ->select('*')
            ->get();

        $doctor = DB::table('users')
            ->leftJoin('cargos', 'cargos.cargo', '=', 'users.cargo')
            ->where('cargos.servicio', true)
            ->where('cargos.cargo', 'like', 'MED%')
            ->get();
        /*
        select * from calendarios
        where fecha = '14-02-2023';
        */
        $list_config = DB::table('fichas')
            ->select(['fichas.id_sala', 'salas.descripcion'])
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->where('fichas.fecha', '=', $fecha)
            ->where('fichas.institucion', '=', strval($institucion))
            ->groupBy('fichas.id_sala', 'salas.descripcion',)
            ->orderBy('salas.descripcion', 'asc')
            ->get();
        $r_equipo = [];
        //return sizeof($list_config) ;
        //return $list_config;
        // si el dia x no tiene0 confuracion craamos una configuracion 
        if (sizeof($list_config) == 0) {
            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->leftJoin('configuracions', 'configuracions.id', '=', 'calendariolineals.id_configuracion')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                ->where('configuracions.institucion', '=', strval($institucion))
                ->get();
            if (sizeof($list_config) > 0) {
                try {
                    $salas = DB::table('asignar_config_salas')
                        ->select(['asignar_config_salas.*', 'salas.descripcion'])
                        ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                        ->where('asignar_config_salas.id_conf', '=', $list_config[0]->id_configuracion)
                        ->get();
                    $horario = DB::table('salas')
                        ->select('*')
                        ->leftJoin('asignar_salas', 'asignar_salas.id_sala', '=', 'salas.id')
                        ->leftJoin('conf_salas', 'conf_salas.id', '=', 'asignar_salas.id_conf_sala')
                        ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
                        ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horario')
                        ->whereNotNull('conf_salas.id')
                        ->orderBy('salas.descripcion', 'asc')
                        ->get();
                    $resp = [
                        'salas' => $salas,
                        'salas_diponibles' => $horario,
                        'equipo' => $r_equipo,
                        'lugar' => $lugar,
                        'respues' => '-+-+-+-+-+-+'
                        //'lista_conf' => $list_config,
                        //'casa0' => $list_config[0]->id_configuracion
                    ];
                } catch (\Throwable $th) {
                    return $th;
                }
                return $resp;
            }
            else{

                return $resp = [
                    'salas' => [],
                    'salas_diponibles' => [],
                    'equipo' => $r_equipo,
                    'lugar'=> $lugar,
                    'doctor' => $doctor,
                    '333'=>$institucion,
                    'estado'=> 'no tiene configuracion'
                ];   
            }
        } else {
            //
            $salas = DB::table('calendarios')
                ->select(['salas.*', 'salas.id as id_sala'])
                ->leftJoin('fichas', 'fichas.fecha', '=', 'calendarios.fecha')
                ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                ->leftJoin('viajes', function ($join) {
                    $join->on('viajes.id_sala', '=', 'salas.id')
                        ->on('viajes.fecha', '=', 'calendarios.fecha');
                })
                ->leftJoin('municipios', 'municipios.id', '=', 'viajes.id_municipio')
                ->where('calendarios.fecha', '=', $fecha)
                ->groupBy('salas.id')
                ->get();
            $horarios = DB::table('fichas')
                ->select(['fichas.*', 'viajes.id_municipio', 'municipios.*', 'horarios.*', 'dar_citas.*', 'personas.*',   'salas.descripcion', 'atenders.id_designado', 'fichas.id as id_ficha', 'institucions.*', 'personas.id as id', 'evaluacions.id_persona as id_persona'])
                ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                ->leftJoin("viajes", function ($join) {
                    $join->on("viajes.id_sala", "=", "salas.id");
                    $join->on("viajes.fecha", "=", "fichas.fecha");
                })
                ->leftJoin("municipios", function ($join) {
                    $join->on("municipios.id", "=", "viajes.id_municipio");
                })
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'fichas.id_conf_sala')
                ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                ->leftJoin('atenders', 'atenders.id_ficha', '=', 'fichas.id')
                ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
                ->leftJoin('evaluacions', 'personas.id', '=', 'evaluacions.id_persona')
                ->leftJoin("calendarios", function ($join) {
                    $join->on("calendarios.fecha", "=", "fichas.fecha");
                })
                ->leftJoin("institucions", function ($join) {
                    $join->on("institucions.codigo", "=", "calendarios.codigo");
                })
                //->rigthJoin('atenders', 'atenders.id_ficha', 'fichas.id')
                //->leftJoin('atenders', 'fichas.id', '=', 'atenders.id_ficha')
                ->where('fichas.fecha', '=', $fecha)
                //->where('fichas.id_sala', '=', $value->id_sala)
                ->orderBy('salas.descripcion', 'asc')

                //->groupBy('id_sala', 'salas.descripcion')
                ->get();




            $resp = [
                'salas' => $salas,
                
                'salas_diponibles' => $horarios,
                'equipo' => $r_equipo,
                'lugar' => $lugar,
                
                'doctor' => $doctor,
                'respues' => 'ssssssss',
            ];
            return $resp;
        }

        $resp = [
            'salas' => [],
            'salas_diponibles' => [],
            'equipo' => $r_equipo,
            'lugar'=> $lugar,
            'noooo'=>'sss'
        ];
        return $resp;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $fecha)
    {
        $doctor = DB::table('users')
            ->leftJoin('cargos', 'cargos.cargo', '=', 'users.cargo')
            ->where('cargos.servicio', true)
            ->where('cargos.cargo', 'like', 'MED%')
            ->get();
        
        $results = DB::table(DB::raw("(select  CASE WHEN to_char(fecha, 'day') = 'saturday' THEN  DATE(fecha::date + INTERVAL '1 days') WHEN TRIM(to_char(fecha, 'day')) = 'sunday' THEN  DATE(fecha::date + INTERVAL '2 days')
            ELSE fecha END as fechaf
            from calendarios
            where atencion = 'feriado' 
            and TRIM(to_char(fecha, 'yyyy')) = TRIM(to_char('" . $fecha . "'::date, 'yyyy'))  ) AS subquery"))
            ->where('subquery.fechaf', '=', $fecha)
            ->get();
        if (count($results) > 0) {
            $resp = [
                'salas' => [],
                'salas_diponibles' => [],
                'equipo' => [],
                //'lista_conf' => $list_config,
                //'casa0' => $list_config[0]->id_configuracion
            ];
            return $resp;
        }
        $lugar = DB::table('institucions')
            ->select('*')
            ->get();
        /*
        select * from calendarios
        where fecha = '14-02-2023';
        */
        $list_config = DB::table('fichas')
            ->select(['fichas.id_sala', 'salas.descripcion'])
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->where('fichas.fecha', '=', $fecha)
            ->groupBy('fichas.id_sala', 'salas.descripcion',)
            ->orderBy('salas.descripcion', 'asc')
            ->get();
        $r_equipo = [];
        //return sizeof($list_config) ;
        //return $list_config;
        // si el dia x no tiene confuracion craamos una configuracion 
        if (sizeof($list_config) == 0) {
            /*
            select * from calendariolineals
            where fecha_inicio <= '2023-02-22' and
            fecha_final >= '2023-02-22';
            */
            $list_config2 = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get();

            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get();
            if (sizeof($list_config) > 0) {
                try {
                    $salas = DB::table('asignar_config_salas')
                        ->select(['asignar_config_salas.*', 'salas.descripcion'])
                        ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                        ->where('asignar_config_salas.id_conf', '=', $list_config[0]->id_configuracion)
                        ->get();
                    $horario = DB::table('asignar_config_salas')
    ->select('*')
    ->leftJoin('salas', 'asignar_config_salas.id_sala', '=', 'salas.id')
    ->leftJoin('conf_salas', 'conf_salas.id', '=', 'asignar_config_salas.id_conf_sala')
    ->leftJoin('asignar_horarios', function ($join) {
        $join->on('asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
            ->on('asignar_config_salas.id_conf_sala', '=', 'asignar_horarios.id_conf_sala');
    })
    ->leftJoin('horarios', function ($join) {
        $join->on('horarios.id', '=', 'asignar_horarios.id_horario')
            ->on('conf_salas.id', '=', 'asignar_config_salas.id_conf_sala');
    })
    ->where('asignar_config_salas.id_conf', '=', $list_config[0]->id_configuracion)
    ->orderBy('salas.descripcion', 'ASC')
    ->get();

                    $resp = [
                        'salas' => $salas,
                        'salas_diponibles' => $horario,
                        'equipo' => $r_equipo,
                        'lugar' => $lugar,
                        'respues' => '-+-+-+-+-+-+',
                        'doctor' => $doctor,
                        //'lista_conf' => $list_config,
                        //'casa0' => $list_config[0]->id_configuracion
                    ];
                } catch (\Throwable $th) {
                    return $th;
                }
                return $resp;
            }
        } else {
            //
            $salas = DB::table('calendarios')
                ->select(['salas.*', 'salas.id as id_sala'])
                ->leftJoin('fichas', 'fichas.fecha', '=', 'calendarios.fecha')
                ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                ->leftJoin('viajes', function ($join) {
                    $join->on('viajes.id_sala', '=', 'salas.id')
                        ->on('viajes.fecha', '=', 'calendarios.fecha');
                })
                ->leftJoin('municipios', 'municipios.id', '=', 'viajes.id_municipio')
                ->where('calendarios.fecha', '=', $fecha)
                ->groupBy('salas.id')
                ->get();
            $horarios = DB::table('fichas')
                ->select(['fichas.*', 'viajes.id_municipio', 'municipios.*', 'horarios.*', 'dar_citas.*', 'personas.*',   'salas.descripcion', 'atenders.id_designado', 'fichas.id as id_ficha', 'institucions.*', 'personas.id as id', 'evaluacions.id_persona as id_persona'])
                ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                ->leftJoin("viajes", function ($join) {
                    $join->on("viajes.id_sala", "=", "salas.id");
                    $join->on("viajes.fecha", "=", "fichas.fecha");
                })
                ->leftJoin("municipios", function ($join) {
                    $join->on("municipios.id", "=", "viajes.id_municipio");
                })
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'fichas.id_conf_sala')
                ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                ->leftJoin('atenders', 'atenders.id_ficha', '=', 'fichas.id')
                ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
                ->leftJoin('evaluacions', 'personas.id', '=', 'evaluacions.id_persona')
                ->leftJoin("calendarios", function ($join) {
                    $join->on("calendarios.fecha", "=", "fichas.fecha");
                })
                ->leftJoin("institucions", function ($join) {
                    $join->on("institucions.codigo", "=", "calendarios.codigo");
                })
                //->rigthJoin('atenders', 'atenders.id_ficha', 'fichas.id')
                //->leftJoin('atenders', 'fichas.id', '=', 'atenders.id_ficha')
                ->where('fichas.fecha', '=', $fecha)
                //->where('fichas.id_sala', '=', $value->id_sala)
                ->orderBy('salas.descripcion', 'asc')

                //->groupBy('id_sala', 'salas.descripcion')
                ->get();




            $resp = [
                'salas' => $salas,
                'salas_diponibles' => $horarios,
                'equipo' => $r_equipo,
                'lugar' => $lugar,
                'respues' => 'ssssssss',
                'doctor' => $doctor,
            ];
            return $resp;
        }
        //$salas = [];

        $resp = [
            'salas' => [],
            'salas_diponibles' => [],
            'equipo' => $r_equipo,
            'lugar' => $lugar,
            'doctor' => $doctor,

        ];
        return $resp;
        //
        return 'estaas con';
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
