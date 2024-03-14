<?php

namespace App\Http\Controllers;

use App\Models\cita_tiene_configuracion;
use App\Http\Controllers\Controller;
use App\Models\atender;
use App\Models\equipo;
use App\Models\Horario;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class CitaTieneConfiguracionController extends Controller
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
     * @param  \App\Models\cita_tiene_configuracion  $cita_tiene_configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(string $fecha)
    {
        /*$query = DB::table("calendarios")
        ->select(DB::raw("case when to_char (fecha, 'day') = 'saturday' then date (fecha::date + interval '1 days') when trim (to_char(fecha, 'day')) = 'sunday' then date (fecha::date + interval '2 days') else fecha end as fecha"))
        ->where("atencion", "=", 'feriado')
        ->where(DB::raw("TRIM(to_char(fecha, 'yyyy'))"),'=', db::raw("TRIM('".$year."')"))
        ->get();*/
        $results = DB::table(DB::raw("(select  CASE WHEN to_char(fecha, 'day') = 'saturday' THEN  DATE(fecha::date + INTERVAL '1 days') WHEN TRIM(to_char(fecha, 'day')) = 'sunday' THEN  DATE(fecha::date + INTERVAL '2 days')
        ELSE fecha END as fechaf
        from calendarios
where atencion = 'feriado' and TRIM(to_char(fecha, 'yyyy')) = TRIM(to_char('" . $fecha . "'::date, 'yyyy'))  ) AS subquery"))
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
        $r_equipo = DB::table('fichas')
        ->select(['fichas.id_sala', 'salas.descripcion'])
        ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
        ->where('fichas.fecha', '=', $fecha)
        ->groupBy('fichas.id_sala', 'salas.descripcion',)
        ->orderBy('salas.descripcion', 'asc')
        ->get();;
        //return sizeof($list_config) ;
        //return $list_config;
        // si el dia x no tiene confuracion craamos una configuracion 
        if (sizeof($list_config) == 0) {
            /*
            select * from calendariolineals
            where fecha_inicio <= '2023-02-22' and
            fecha_final >= '2023-02-22';
            */
            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get();
            if (sizeof($list_config) > 0) {
                try {
                    $salas = DB::table('asignar_config_salas')
                        ->select(['*', 'salas.descripcion as descripcion'])
                        ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                        ->leftJoin('configuracions', 'configuracions.id',  '=', 'asignar_config_salas.id_conf')
                        ->where('asignar_config_salas.id_conf', '=', $list_config[0]->id_configuracion)
                        ->get();
                    $horario = [];
                    foreach ($salas as $key => $value) {
                        /*
                        
                        */
                        $h =
                            DB::table("salas")
                            //->select(['*', 'fichas.id_ficha as id_fichas'])
                            //->leftJoin('designar_equipo_lineals', 'designar_equipo_lineals.id_sala', '=', 'salas.id')
                            ->leftJoin("asignar_config_salas", function ($join) use ($value) {
                                $join->on("asignar_config_salas.id_sala", "=", "salas.id");
                                $join->where("asignar_config_salas.id_sala", "=",  $value->id_sala);
                            })
                            ->leftJoin("conf_salas", function ($join)  use ($value) {
                                $join->on("conf_salas.id", "=", "asignar_config_salas.id_conf_sala");
                                $join->where("conf_salas.id", "=", $value->id_conf_sala);
                            })
                            ->leftJoin("asignar_horarios", function ($join) {
                                $join->on("asignar_horarios.id_conf_sala", "=", "conf_salas.id");
                            })
                            ->leftJoin("horarios", function ($join) {
                                $join->on("horarios.id", "=", "asignar_horarios.id_horario");
                            })
                            /*->leftJoin("viajes", function ($join)  {
                                $join->on("viajes.id_sala", "=", "salas.id");
                            })
                            ->leftJoin("municipios", function ($join)  {
                                $join->on("municipios.id", "=", "viajes.id_municipio");
                            })*/
                            ->whereNotNull('conf_salas.id')
                            //->where('conf_salas.id','=',$value->id_conf)
                            ->where('salas.id', '=', $value->id_sala)
                            ->where("asignar_config_salas.id_conf", "=", $value->id_conf)
                            
                            ->orderBy('salas.descripcion', 'asc')
                            //->leftJoin('asignar_config_salas',  "asignar_config_salas.id_sala", "=", "salas.id" )
                            //->where("salas.id", "=", $value->id_sala)
                            //->where('asignar_config_salas.id_conf', '=', $list_config[0]->id_configuracion)
                            ->get();
                        array_push($horario, $h);
                    }
                    $resp = [
                        'salas' => $salas,
                        'salas_diponibles' => $horario,
                        'equipo' => $horario,
                        'respues' => '-+-+-0000+-+-+-+'
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
            /*->leftJoin('viajes', function ($join) {
                $join->on('viajes.id_sala', '=', 'salas.id')
                     ->on('viajes.fha', '=', 'calendarios.fecha');
            })
            ->leftJoin('municipios', 'municipios.id', '=', 'viajes.id_municipio')
            */
            ->where('fichas.fecha', '=', $fecha)
            ->groupBy('salas.id')
            ->get();
        


            $horarios =  [];

            foreach ($list_config as $key => $value) {
                # code...
                /*
                
                */

                $horario = DB::table('fichas')
                    ->select(['fichas.*', 'viajes.id_municipio','municipios.*', 'horarios.*', 'dar_citas.*', 'personas.*',   'salas.descripcion', 'atenders.id_designado', 'fichas.id as id_ficha', 'institucions.*', 'personas.id as id', 'evaluacions.id_persona as id_persona'])
                    ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                    ->leftJoin("viajes", function ($join)  {
                        $join->on("viajes.id_sala", "=", "salas.id");
                        $join->on("viajes.fecha", "=", "fichas.fecha");
                    })
                    ->leftJoin("municipios", function ($join)  {
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
                        $join->on( "institucions.codigo", "=", "calendarios.codigo");
                    })
                    //->rigthJoin('atenders', 'atenders.id_ficha', 'fichas.id')
                    //->leftJoin('atenders', 'fichas.id', '=', 'atenders.id_ficha')
                    ->where('fichas.fecha', '=', $fecha)
                    ->where('fichas.id_sala', '=', $value->id_sala)
                 //   ->where('fichas.id_conf_sala', '=', $value->id_conf_sala)
                    ->orderBy('salas.descripcion', 'asc')

                    //->groupBy('id_sala', 'salas.descripcion')
                    ->get();

                array_push($horarios, $horario);
            }
            $resp = [
                'salas' => $salas,
                'salas_diponibles' => $horarios,
                'equipo' => $r_equipo,

                'respues'=> 'ssssssss',
            ];
            return $resp;
        }
        //$salas = [];

        $resp = [
            'salas' => [],
            'salas_diponibles' => [],
            'equipo' => $r_equipo

        ];
        return $resp;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cita_tiene_configuracion  $cita_tiene_configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(cita_tiene_configuracion $cita_tiene_configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cita_tiene_configuracion  $cita_tiene_configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cita_tiene_configuracion $cita_tiene_configuracion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cita_tiene_configuracion  $cita_tiene_configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(cita_tiene_configuracion $cita_tiene_configuracion)
    {
        //
    }
    public static function verificar_fecha(String $fecha)
    {
        $list_config = DB::table('cita_tiene_configuracions')
            ->select('*')
            ->leftJoin('configuracions', 'configuracions.id', '=', 'cita_tiene_configuracions.id')
            ->where('fecha', '>=', date($fecha))
            ->where('atencion', '=', 'true')
            ->get();
        $verificar = false;
        if (count($list_config) == 0) {
            $verificar = true;
        }
        $resp = [
            'lista_fechas' => $list_config,
            'verificar' => $verificar,
            'n' => count($list_config)
        ];
        return $resp;
    }
    public static function verificar_rangofecha(Request $request)
    {
        $temp = [];
        $fecha = $request['fecha'];
        $conf_default = [];
        foreach ($fecha as $key => $value) {
            try {
                $list_config = DB::table('cita_tiene_configuracions')
                    ->select('*')
                    ->where('fecha', '=', date($value))
                    ->get();
                $fecha_entre = DB::table('configuracions')
                    ->select('*')
                    ->where('fecha_inicio', '<=', date($value))
                    ->where('fecha_final', '>=', date($value))
                    ->where('tipo', '=', 'permanente')->get();
            } catch (\Throwable $th) {
                return $th;
            }
            if (count($list_config) > 0) {


                array_push($temp, $value);
            } else {
                array_push($conf_default, $fecha_entre[0]);
            }
        }
        $verificar = false;
        if (count($temp) == 0) {
            $verificar = true;
        }

        $resp = [
            'lista_fechas' => $temp,
            'verificar' => $verificar,
            'default' => $conf_default,
            'n' => count($temp)
        ];
        return $resp;
    }
}
