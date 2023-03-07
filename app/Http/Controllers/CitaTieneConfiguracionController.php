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
        /*
        select * from calendarios
        where fecha = '14-02-2023';
        */

        $list_config = DB::table('fichas')
            ->select('id_sala', 'salas.descripcion')
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->where('fecha', '=', $fecha)
            ->groupBy('id_sala', 'salas.descripcion')
            ->orderBy('salas.descripcion', 'asc')
            ->get();
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
        //return sizeof($list_config) ;
        //return $list_config;
        // si el dia x no tiene confuracion craamos una configuracion 
        if (sizeof($list_config) == 0 ) {
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
                        ->select('*')
                        ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                        ->where('id_conf', '=', $list_config[0]->id_configuracion)
                        
                        ->get();
                    $horario = [];
                    foreach ($salas as $key => $value) {
                        /*
                        
                        */
                        $h =
                        DB::table("salas")
                        ->leftJoin("asignar_salas", function ($join) use ($value) {
                            $join->on("asignar_salas.id_sala", "=", "salas.id");
                            $join->where("asignar_salas.id_sala", "=",  $value->id_sala);
                        })
                        ->leftJoin("conf_salas", function ($join)  use ($value) {
                            $join->on("conf_salas.id", "=","asignar_salas.id_conf_sala" );
                            $join->where("conf_salas.id", "=", $value->id_conf_sala);
                            
                        })
                        ->leftJoin("asignar_horarios", function ($join) {
                            $join->on("asignar_horarios.id_conf_sala", "=", "conf_salas.id");
                        })
                        ->leftJoin("horarios", function ($join) {
                            $join->on("horarios.id", "=", "asignar_horarios.id_horario");
                        })
                        ->orderBy('salas.descripcion', 'asc')
                        ->whereNotNull('conf_salas.id')
                        //->leftJoin('asignar_config_salas',  "asignar_config_salas.id_sala", "=", "salas.id" )
                        //->where("salas.id", "=", $value->id_sala)
                        //->where("conf_salas.id", "=", $value->id_conf_sala)
                        //->where('asignar_config_salas.id_conf', '=', $list_config[0]->id_configuracion)
                        ->get();
                        array_push($horario, $h);
                    }
                    /*
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
            //$salas;
            $horarios =  [];
            $sql = '';
            foreach ($list_config as $key => $value) {
                # code...
                $horario = DB::table('fichas')
                    ->select(['fichas.*','horarios.*','dar_citas.*','personas.*', 'atenders.id_designado'])
                    ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                    ->leftJoin('conf_salas', 'conf_salas.id', '=', 'fichas.id_sala')
                    ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                    ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                    ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
                    //->rigthJoin('atenders', 'atenders.id_ficha', 'fichas.id')
                    ->leftJoin('atenders', 'fichas.id', '=', 'atenders.id_ficha')
                    ->where('fecha', '=', $fecha)
                    ->where('fichas.id_sala', '=', $value->id_sala)

                    ->orderBy('salas.descripcion', 'asc')
                    
                    //->groupBy('id_sala', 'salas.descripcion')
                    ->get();
                
                array_push($horarios, $horario);

                
            }
            $resp = [
                'salas' => $list_config,
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
