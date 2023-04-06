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
            ->select([ 'fichas.id_sala', 'salas.descripcion', 'designar_equipos.id_equipo', 'equipos.nombre_equipo'])
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->leftJoin('designar_equipos', 'designar_equipos.id_sala', '=', 'salas.id')
            ->leftJoin('equipos', 'equipos.id', '=', 'designar_equipos.id')
            ->where('fichas.fecha', '=', $fecha)
            ->where('designar_equipos.fecha', '=', $fecha)
            ->groupBy('fichas.id_sala', 'salas.descripcion', 'designar_equipos.id_equipo', 'equipos.nombre_equipo')
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
            $equipos = DB::table('equipos')
                ->select('*')
                ->leftJoin('designar_equipo_lineals', 'designar_equipo_lineals.id_equipo', '=', 'equipos.id')
                ->where('designar_equipo_lineals.id_conf', '=', $list_config2[0]->id_configuracion)
                ->get();
            $r_equipo = [];
            foreach ($equipos as $key => $value) {
                $lista_equipos = DB::table('equipos')
                    ->select('*')
                    ->leftJoin('asignar_equipos', 'asignar_equipos.id_equipo', '=', 'equipos.id')
                    ->leftJoin('users', 'users.id', '=', 'asignar_equipos.id_usuario')
                    ->where('equipos.id', '=', $value->id)
                    ->get();
                $a =  [
                    'equipo' => $value,
                    'lista' => $lista_equipos
                ];
                array_push($r_equipo, $a);
            }

            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get();
            if (sizeof($list_config) > 0) {
                try {
                    $salas = DB::table('asignar_config_salas')
                        ->select(['asignar_config_salas.*', 'salas.descripcion','equipos.nombre_equipo' ])
                        ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                        ->leftJoin('designar_equipo_lineals', 'designar_equipo_lineals.id_sala', '=', 'salas.id')
                        ->leftJoin('equipos', 'equipos.id', '=', 'designar_equipo_lineals.id_equipo')
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

                            ->leftJoin("asignar_salas", function ($join) use ($value) {
                                $join->on("asignar_salas.id_sala", "=", "salas.id");
                                $join->where("asignar_salas.id_sala", "=",  $value->id_sala);
                            })
                            ->leftJoin("conf_salas", function ($join)  use ($value) {
                                $join->on("conf_salas.id", "=", "asignar_salas.id_conf_sala");
                                $join->where("conf_salas.id", "=", $value->id_conf_sala);
                            })
                            ->leftJoin("asignar_horarios", function ($join) {
                                $join->on("asignar_horarios.id_conf_sala", "=", "conf_salas.id");
                            })
                            ->leftJoin("horarios", function ($join) {
                                $join->on("horarios.id", "=", "asignar_horarios.id_horario");
                            })
                            ->whereNotNull('conf_salas.id')
                            //->where('conf_salas.id','=',$value->id_conf)
                            ->where('salas.id', '=', $value->id_sala)
                            ->orderBy('salas.descripcion', 'asc')
                            //->leftJoin('asignar_config_salas',  "asignar_config_salas.id_sala", "=", "salas.id" )
                            //->where("salas.id", "=", $value->id_sala)
                            //->where("conf_salas.id", "=", $value->id_conf_sala)
                            //->where('asignar_config_salas.id_conf', '=', $list_config[0]->id_configuracion)
                            ->get();
                        array_push($horario, $h);
                    }
                    /*

select Object { data: {…}, status: 200, statusText: "OK", headers: {…}, config: {…}, request: XMLHttpRequest }
​
config: Object { url: "/main/lista_configuracion/2023-03-16", method: "get", timeout: 0, … }
​
data: Object { salas: (4) […], salas_diponibles: (4) […], equipo: (2) […] }
​​
equipo: Array [ {…}, {…} ]
​​
salas: Array(4) [ {…}, {…}, {…}, … ]
​​
salas_diponibles: Array(4) [ 'select * from "salas" left join "designar_equipo_lineals" on "designar_equipo_lineals"."id_sala" = "salas"."id" left join "asignar_salas" on "asignar_salas"."id_sala" = "salas"."id" and "asignar_salas"."id_sala" = ? left join "conf_salas" on "conf_salas"."id" = "asignar_salas"."id_conf_sala" and "conf_salas"."id" = ? left join "asignar_horarios" on "asignar_horarios"."id_conf_sala" = "conf_salas"."id" left join "horarios" on "horarios"."id" = "asignar_horarios"."id_horario" where "conf_salas"."id" is not null order by "salas"."descripcion" asc', 'select * from "salas" left join "designar_equipo_lineals" on "designar_equipo_lineals"."id_sala" = "salas"."id" left join "asignar_salas" on "asignar_salas"."id_sala" = "salas"."id" and "asignar_salas"."id_sala" = ? left join "conf_salas" on "conf_salas"."id" = "asignar_salas"."id_conf_sala" and "conf_salas"."id" = ? left join "asignar_horarios" on "asignar_horarios"."id_conf_sala" = "conf_salas"."id" left join "horarios" on "horarios"."id" = "asignar_horarios"."id_horario" where "conf_salas"."id" is not null order by "salas"."descripcion" asc', 'select * from "salas" left join "designar_equipo_lineals" on "designar_equipo_lineals"."id_sala" = "salas"."id" left join "asignar_salas" on "asignar_salas"."id_sala" = "salas"."id" and "asignar_salas"."id_sala" = ? left join "conf_salas" on "conf_salas"."id" = "asignar_salas"."id_conf_sala" and "conf_salas"."id" = ? left join "asignar_horarios" on "asignar_horarios"."id_conf_sala" = "conf_salas"."id" left join "horarios" on "horarios"."id" = "asignar_horarios"."id_horario" where "conf_salas"."id" is not null order by "salas"."descripcion" asc', … ]
​​
<prototype>: Object { … }
​
headers: Object { "cache-control": "no-cache, private", connection: "Keep-Alive", "content-type": "application/json", … }
​
request: XMLHttpRequest { readyState: 4, timeout: 0, withCredentials: false, … }
​
status: 200
​
statusText: "OK"
​
<prototype>: Object { … }
Agenda2.vue:442

                    
                        *          
                        */
                    //$salas_disponibles =[];

                    /*
                        $salas_disponibles = DB::table('salas')
                            //->select('salas.sala, salas.descripcion')
                            ->join('horarios', "horarios.sala", '=', "salas.sala")
                            ->leftJoin("agendas", function ($join) use ($fecha) {
                                $join->on("agendas.horario", "=", "horarios.id_horario");
                                $join->where('agendas.fecha', '=', $fecha);
                            })
                            ->whereNull('agendas.horario')
                            ->where('salas.id', '=', $list_config[0]->id)
                            ->groupBy('salas.sala', 'salas.descripcion')
                            ->select('salas.sala', 'salas.descripcion')
                            ->orderBy('salas.descripcion')
                            ->get();
                            */
                    $resp = [
                        'salas' => $salas,
                        'salas_diponibles' => $horario,
                        'equipo' => $r_equipo,
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
            $salas =
            DB::table("calendarios")
            ->select(['designar_equipos.*', 'salas.descripcion','equipos.nombre_equipo' ])
                        
            ->leftJoin("designar_equipos", function($join){
                $join->on("designar_equipos.fecha", "=", "calendarios.fecha");
            })
            ->leftJoin("equipos", function($join){
                $join->on("equipos.id", "=", "designar_equipos.id_equipo");
            })
            ->leftJoin("salas", function($join){
                $join->on("salas.id", "=", "designar_equipos.id_sala");
            })
            ->where('calendarios.fecha', '=', $fecha)
            ->get();
            
            

            $horarios =  [];

            foreach ($list_config as $key => $value) {
                # code...
                /*
                
                */
                $horario = DB::table('fichas')
                    ->select(['fichas.*', 'horarios.*', 'dar_citas.*', 'personas.*', 'designar_equipos.id_equipo', 'designar_equipos.id_sala as id_sala_asig', 'equipos.nombre_equipo', 'salas.descripcion', 'atenders.id_designado', 'fichas.id as id_ficha'])
                    ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                    ->leftJoin('designar_equipos', 'designar_equipos.id_sala', '=', 'salas.id')
                    ->leftJoin('equipos', 'equipos.id', '=', 'designar_equipos.id_equipo')
                    ->leftJoin('conf_salas', 'conf_salas.id', '=', 'fichas.id_conf_sala')
                    ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                    ->leftJoin('atenders', 'atenders.id_ficha', '=', 'fichas.id')
                    ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                    ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
                    //->rigthJoin('atenders', 'atenders.id_ficha', 'fichas.id')
                    //->leftJoin('atenders', 'fichas.id', '=', 'atenders.id_ficha')
                    ->where('fichas.fecha', '=', $fecha)
                    ->where('fichas.id_sala', '=', $value->id_sala)
                    ->where('designar_equipos.fecha', '=', $fecha)
                    ->orderBy('salas.descripcion', 'asc')

                    //->groupBy('id_sala', 'salas.descripcion')
                    ->get();

                array_push($horarios, $horario);
            }
            $resp = [
                'salas' => $salas,
                'salas_diponibles' => $horarios,
                'equipo' => $r_equipo,


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
        /*
        select cita_tiene_configuracions.fecha, 
        SUM(CASE WHEN  not agendas.consultorio IS NULL  THEN 1 ELSE 0 END) as uso, 
        SUM(CASE WHEN agendas.consultorio IS NULL and not salas.id is null  THEN 1 ELSE 0 END) AS disponibles, 
        count(salas.id) as total
        --, horarios.id_horario, horarios.hora_inicio, horarios.sala, agendas.codigo_cita 
        from cita_tiene_configuracions
        left join salas on salas.id = cita_tiene_configuracions.id
        left join horarios on horarios.sala = salas.sala
        LEFT join agendas on  agendas.fecha = cita_tiene_configuracions.fecha and agendas.consultorio = salas.sala and agendas.horario = horarios.id_horario
        --where not salas.id is null and 
        --where cita_tiene_configuracions.fecha >= '2022-11-29'
        GROUP by cita_tiene_configuracions.fecha
        --having SUM(CASE WHEN agendas.consultorio IS NULL and not salas.id is null  THEN 1 ELSE 0 END)> 0
        order by cita_tiene_configuracions.fecha

        select *
        --cita_tiene_configuracions.fecha, 
        from cita_tiene_configuracions
        left join configuracions ON configuracions.id = cita_tiene_configuracions.id
        where atencion != true
        
        
        
        */
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
