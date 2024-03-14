<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Http\Controllers\Controller;
use App\Models\conf_sala;
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
                ->leftJoin('agendas', function ($join) use ($fecha) {
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
    public static function lista_horario(Request $request)
    {
        //
        //return 'ss';
        /*
select * from conf_salas
left join salas ON salas.id = conf_salas.id_sala
left join horarios on horarios.id_horario = conf_salas.id_horario
left join institucions ON institucions.codigo = salas.institucion
where id_configuracion = '1'  and id_sala=1 and id_institucion = '01'*/

        try {
            $horarios =  DB::table('conf_salas')
                ->select('*')
                ->leftJoin('salas', 'salas.id', '=', 'conf_salas.id_sala')
                ->leftJoin('horarios', 'horarios.id_horario', '=', 'conf_salas.id_horario')
                ->leftJoin('institucions', 'institucions.codigo', '=', 'salas.institucion')
                ->where('id_configuracion', '=', $request['id_configuracion'])
                ->where('id_sala', '=', $request['id_sala'])
                ->where('id_institucion', '=', $request['id_institucion'])
                ->get();
        } catch (\Throwable $th) {
            return $th;
        }
        return $horarios;
    }
    public static function crear_horario_individual(Request $request)
    {
        //return $request;
        $lugar = $request['lugar'];
        $fecha = $request['fecha'];
        $fichas = DB::table('fichas')
            ->leftJoin('salas', 'fichas.id_sala', '=', 'salas.id')
            ->where('fecha', '=', $fecha)
            ->get();
        if (count($fichas) == 0) {
            
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
            //return $request;
        }
        $salas = DB::table('fichas')
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->where('fecha', $fecha)
            ->where('salas.descripcion', 'like', 'salaauxiliar%')
            ->groupBy('salas.descripcion')
            ->select('salas.descripcion')
            ->get();

        $salas2 = DB::table('salas')
            ->where('descripcion', 'like', 'salaauxiliar%')
            ->get();
        $conf_salas = DB::table('conf_salas')
            ->where('id', '=', $request['id_conf_sala'])
            ->first();
        
        if (count($salas) ) {
            $id = DB::table('salas')->insertGetId([
                'descripcion' => 'salaauxiliar ' . count($salas2),
                'institucion' => $lugar
            ]);
            $salas2 = DB::table('salas')
                ->where('descripcion', '=', 'salaauxiliar ' . count($salas2))
                ->get();

        }
        $conf_opcion = DB::table('conf_salas')
            ->where('tiempo_apertura', '=', $request['hora_inicio'])
            ->where('tiempo_cierre', '=', $request['hora_final'])
            ->where('min_promedio_atencion', '=', $conf_salas->min_promedio_atencion)
            ->get();
        if (count($conf_opcion) == 0) {
            $conf_opcion = DB::table('conf_salas')
                ->insertGetId([
                    'tiempo_apertura' => $request['hora_inicio'],
                    'tiempo_cierre' => $request['hora_final'],
                    'min_promedio_atencion' => $conf_salas->min_promedio_atencion
                ]);
        }
        $conf_opcion = DB::table('conf_salas')
        ->where('tiempo_apertura', '=', $request['hora_inicio'])
        ->where('tiempo_cierre', '=', $request['hora_final'])
        ->where('min_promedio_atencion', '=', $conf_salas->min_promedio_atencion)
        ->first();

        DB::table('fichas')->insert([
            'institucion' => $lugar,
            'id_sala' => $salas2[0]->id,
            'id_horario' => $request['id_horario'],
            'id_conf_sala' => $conf_opcion->id,
            'fecha' => $fecha
        ]);
    }
}
