<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Exception;
use Exception as GlobalException;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Response;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * 
     * SELECT date_trunc('day', dd):: date as dia, To_Char(date_trunc('day', dd):: date , 'd')
FROM generate_series
        ( CURRENT_DATE::timestamp 
        , (CURRENT_DATE+interval '1 year')::timestamp
        , '1 day'::interval) as dd
where  date_trunc('day', dd):: date >='2022-11-17' 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //


        /*
        
select configuracions.id, count(configuracions.id) fechas_uso, 
configuracions.fecha_inicio, configuracions.fecha_final, configuracions.tipo,
configuracions.principal, configuracions.feriado
from configuracions
left join cita_tiene_configuracions on configuracions.id = cita_tiene_configuracions.id  
where configuracions.fecha_final >= '2022-11-15' or configuracions.feriado = 'true'
group by configuracions.id

        */

        date_default_timezone_set("America/La_Paz");
        $date = date_create();

        $list_config = DB::table('configuracions')
            ->select(
                '*'
                //DB::raw('configuracions.tipo, COALESCE(cita_tiene_configuracions.fecha,configuracions.fecha_inicio) as fecha_inicio')
            )

            ->get();
        $tabla = 'calendariolineals';
        $linea_calendarios = DB::table($tabla)
            ->select('calendariolineals.id as id_calendario', '*')
            ->leftJoin('configuracions', 'configuracions.id', '=', $tabla . '.id_configuracion')
            //->leftJoin('configuracions', 'configuracions.id','=',$tabla.'.id_configuracion')
            ->orderBy('fecha_inicio', 'asc')
            ->get();
        /*$fechas_no_validas = DB::table('configuracions')
            ->select(
                'cita_tiene_configuracions.fecha',
            )
            ->leftJoin('cita_tiene_configuracions',  function ($join) use ($date) {
                $join->on('configuracions.id', '=', 'cita_tiene_configuracions.id');
                //date_format($date, "Y-m-d"));
            })
            ->where('fecha', '>=', date_format($date, "Y-m-d"))
            ->where('clase', '=', 'Feriado')
            ->get();*/



        //->whereRaw('fecha_agenda','>=',date_format($date, "Y-m-d"))
        //->join('cita_tiene_configuracions.fecha', '>=', date_format($date, "Y-m-d"))
        //->get();
        $date = date_format($date, "Y-m-d");
        return inertia('Configuracions', [
            'configuracion' => $list_config,
            'fecha_server' => $date,
            'fechas_no_validas' => [],
            'calendario' => $linea_calendarios
            //array_column(json_decode($fechas_no_validas), 'fecha'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $antigua = $request['configuracion_antigua'];
        $configuracion = $request['configuracion'];
        $fecha = $request['fecha_nueva'];
        $table = 'calendariolineals';
        $salas =  $request['salas'];
        return $salas;
        //return $configuracion;
        
        try {
            $id_config =  DB::table('configuracions')
                ->insertGetId($configuracion);
        } catch (\Throwable $th) {
            return Response::json(['mensaje' => $th->getMessage()], 500);
        }
        try {

            $conf_antigua = DB::table($table)
                ->where('id', '=', $antigua['id_calendario'])
                ->get()[0];

            //return $conf_antigua;
            //$conf_antigua->fecha_final = date('Y-m-d', strtotime($nueva['fecha_inicio'] . ' - 1 days'));
            //unset($conf_antigua->id);
            $resul = DB::table($table)
                ->where('id', '=', $antigua['id_calendario'])
                ->update(['fecha_final' => date('Y-m-d', strtotime($fecha . ' - 1 days')), 'principal' => false]);

            //DB::table(self::$table)->update($antigua);
        } catch (\Throwable $th) {
            DB::table('configuracions')->delete($id_config);
            return Response::json(['mensaje' => $th->getMessage()], 500);
        }
        try {
            $conf_nueva = clone $conf_antigua;
            $conf_nueva->fecha_inicio = $fecha;
            $conf_nueva->fecha_final = $conf_antigua->fecha_final;
            $conf_nueva->historial = $conf_antigua->id;
            $conf_nueva->id_configuracion = $id_config;

            unset($conf_nueva->id);
            $id_nueva = DB::table($table)->insertGetId((array)$conf_nueva);
        } catch (\Throwable $th) {
            DB::table('configuracions')->delete($id_config);
            $resul = DB::table($table)
                ->where('id', '=',  $antigua['id_calendario'])
                ->update(['fecha_final' => $conf_antigua->fecha_final, 'principal' => $conf_antigua->principal]);
            return Response::json(['mensaje' => $th->getMessage()], 500);
        }

        try {
            //salas
            //return $salas;
            $r = [];
            foreach ($salas as $key => $value) {
                # code...

                $cont_salas = DB::table('salas')
                    ->where('descripcion', '=', $value['descripcion'])
                    ->get();

                if (count($cont_salas) > 0) {
                    $s = $cont_salas[0];
                } else {
                    $s = [
                        'descripcion' => $value['descripcion'],
                        'institucion' => $value['institucion'],
                        'estado' => true,
                    ];
                    $e = DB::table('salas')->insertGetId($s);
                    $s = [
                        'descripcion' => $value['descripcion'],
                        'institucion' => $value['institucion'],
                        'estado' => true,
                        'id' => $e
                    ];
                }
                array_push($r, $s);
                DB::table('asignar_config_salas')->insert([
                    'id_sala'=> $value['id'],
                    'id_conf_sala'=>$value['id_configuracion'],
                    'id_conf'=>$id_nueva
                    ]
                );
            }
            return $salas;
        } catch (\Throwable $th) {
            DB::table('configuracions')->delete($id_config);
            $resul = DB::table($table)
                ->where('id', '=',  $antigua['id_calendario'])
                ->update(['fecha_final' => $conf_antigua->fecha_final, 'principal' => $conf_antigua->principal]);
            DB::table($table)->delete($id_nueva);
            return Response::json(['mensaje' => $th->getMessage()], 500);
        }


        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(int $configuracion)
    {
        //
        $date = '';
        try {
            $dato = DB::table('conf_salas') //->where('id', $configuracion)
                ->select(['salas.id', 'salas.descripcion', 'salas.institucion', 'salas.estado'])
                ->leftJoin('salas', 'salas.id', '=', 'conf_salas.id_sala')
                ->where('conf_salas.id_configuracion', '=', $configuracion)
                ->groupBy('salas.id')
                ->get();
        } catch (\Throwable $th) {
            return $th;
        }
        $n_salas =  count($dato);
        $datos = [
            'salas' => $dato,
            'n_sala' => $n_salas,
            //'horarios' => $horarios
        ];
        return $datos;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracion $configuracion)
    {
        //


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        //

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $configuracion)
    {
        //
        date_default_timezone_set("America/La_Paz");
        $date = date_create();
        try {
            $list_item = DB::table('configuracions')->where('id', '=', $configuracion)->get();
            if (count($list_item) == 1) {
                $item = $list_item[0];
                DB::table('configuracions')->where('historial', '=', $item->id)
                    ->update(['historial' => $item->historial]);
                DB::table('configuracions')->where('id', '=', $item->historial)
                    ->update(['fecha_final' => $item->fecha_final, 'principal' => $item->principal]);
                DB::table('configuracions')->delete($item->id);
                DB::table('salas')->where('id', '=', $item->id)->delete();
            }
        } catch (\Throwable $th) {
            return $th;
        }
        $list_config = DB::table('configuracions')
            ->select('*')
            ->where('fecha_final', '>', date_format($date, "Y-m-d"))
            ->get();
        return $list_config;
    }
    public static function fechas_invalidos()
    {
        /*
        select fecha from configuracions 
left join cita_tiene_configuracions on configuracions.id = cita_tiene_configuracions.id 
where atencion = false;*/
        date_default_timezone_set("America/La_Paz");
        $date = date_create();
        $list_config = DB::table('configuracions')
            ->select(
                'cita_tiene_configuracions.fecha',
            )
            ->leftJoin('cita_tiene_configuracions',  function ($join) use ($date) {
                $join->on('configuracions.id', '=', 'cita_tiene_configuracions.id');
                //date_format($date, "Y-m-d"));
            })
            ->where('configuracions.fecha_final', '>=', date_format($date, "Y-m-d"))
            ->get();
        return $list_config;
    }

    public static function validar_configuracion(Request $request)
    {
        $config =  $request['configuracion'];
        $respuesta = ['validar' => false, 'respuesta' => ''];
        //return $config;
        try {
            $query =  DB::table('configuracions')
                ->where('descripcion', '=', $config['descripcion'])
                ->where('institucion', '=', $config['institucion'])
                ->get();
            if (count($query) == 0) {
                $respuesta['validar'] = true;
            }
            return $respuesta;
        } catch (\Throwable $th) {
            return $th;
            $respuesta['respuesta'] = $th;
            return $respuesta;
        }
    }
}
 //
        /*date_default_timezone_set("America/La_Paz");
        $date = date_create();
        $edit = $request['datos'];
        $n_fecha_final = date($edit['fecha_inicio']);
        $n_fecha_final = date("Y-m-d", strtotime($n_fecha_final . "- 1 days"));

        if ($edit['tipo'] == 'permanente') {

            try {
                unset($edit['fecha_uso']);
                $default_actual =  DB::table('configuracions')
                    ->where('principal', true)
                    ->update(['principal' => false, 'fecha_final' => $n_fecha_final]);

                $edit['historial'] = $edit['id'];
                unset($edit['id']);
                $default_actual =  DB::table('configuracions')->insertGetId($edit);
                $salas = $request['salas'];

                foreach ($salas as $id => $row) {
                    # code...
                    //$row->id;
                    $row['id'] = $default_actual;
                    $horario  =  $row['generar_horario'];
                    unset($row['sala']);
                    unset($row['generar_horario']);
                    DB::table('salas')->insert($row);
                    $id_sala = DB::getPdo()->lastInsertId();;
                    foreach ($horario as $id => $h) {
                        $h['sala'] = $id_sala;
                        DB::table('horarios')->insert($h);
                    }
                }
            } catch (\Throwable $th) {
                return $th;
            }
        }
        if ($edit['tipo'] == 'temporal') {
            try {
                unset($edit['id']);
                $default_actual =  DB::table('configuracions')->insertGetId($edit);
                $temp = $request['fecha_temporales'];
                foreach ($temp as $id => $row) {
                    $p = [
                        'id' => $default_actual,
                        'fecha' => $row
                    ];
                    DB::table('cita_tiene_configuracions')->insert($p);
                }
                if ($edit['atencion']) {
                    $salas = $request['salas'];
                    foreach ($salas as $id => $row) {
                        $row['id'] = $default_actual;
                        $horario  =  $row['generar_horario'];
                        unset($row['sala']);
                        unset($row['generar_horario']);
                        DB::table('salas')->insert($row);
                        $id_sala = DB::getPdo()->lastInsertId();;
                        foreach ($horario as $id => $h) {
                            $h['sala'] = $id_sala;
                            DB::table('horarios')->insert($h);
                        }
                    }
                }
            } catch (\Throwable $th) {
                return $th;
            }
        }
        $list_config = DB::table('configuracions')
            ->select(
                'configuracions.descripcion',
                'configuracions.atencion',
                'configuracions.principal',
                'configuracions.tipo',
                'configuracions.historial',
                'configuracions.id',
                'configuracions.fecha_final',
                DB::raw('configuracions.tipo, COALESCE(cita_tiene_configuracions.fecha,configuracions.fecha_inicio) as fecha_inicio')
            )
            ->leftJoin('cita_tiene_configuracions',  function ($join) use ($date) {
                $join->on('configuracions.id', '=', 'cita_tiene_configuracions.id');
                //date_format($date, "Y-m-d"));
            })
            ->where('configuracions.fecha_final', '>=', date_format($date, "Y-m-d"))
            ->get();
        return $list_config;*/