<?php

namespace App\Http\Controllers;

use App\Models\sala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return inertia('Micomponet/Sala');
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

        $sala =  $request['sala'];
        $horario = $request['horario'];
        //return $sala;
    
        //
        try {
            //se debe de poner la institucion en consulta
            $busqueda_sala =  DB::table('salas')
                ->where('descripcion', '=', $sala['descripcion'])
                ->where('institucion', '=', '01')
                ->get();
            if (count($busqueda_sala) == 0) {
                $datos = [
                    'descripcion' =>  $sala['descripcion'],
                    'institucion' => '01'
                ];
                $id_insert = DB::table('salas')
                    ->insertGetId($datos);
                $sala['id_sala'] =  $id_insert;
            } elseif (count($busqueda_sala) == 1) {
                $sala['id_sala'] =  $busqueda_sala[0]->id;
            }
        } catch (\Throwable $th) {
           
            $rrr = Response::json([
                'error' => $th
            ], 404);
            return $rrr;
        }
        //return $sala;
        try {
            //return $sala;
            if ( ! isset($sala['tiempo_descanso'])) {
                 $sala['tiempo_descanso'] = null;
            }
            elseif($sala['tiempo_descanso']  == 'Fecha inválida') {
                $sala['tiempo_descanso']  = null;
            }
            $busqueda_configuracion =  DB::table('conf_salas')
                ->where('tiempo_apertura', '=', $sala['tiempo_apertura'],)
                ->where('tiempo_cierre', '=', $sala['tiempo_cierre'])
                ->where('tiempo_descanso', '=', isset($sala['tiempo_descanso']) ? $sala['tiempo_descanso'] : null)
                ->where('min_promedio_atencion', '=', $sala['min_promedio_atencion'])
                ->get();
                
            if (count($busqueda_configuracion) == 0) {
                $datos = [
                    'tiempo_apertura' => $sala['tiempo_apertura'],
                    'tiempo_cierre' => $sala['tiempo_cierre'],
                    'tiempo_descanso' => isset($sala['tiempo_descanso']) ? $sala['tiempo_descanso'] : null,
                    'min_promedio_atencion' => $sala['min_promedio_atencion'],

                ];
                $id_conf = DB::table('conf_salas')
                    ->insertGetId($datos);
                $sala['id_conf_sala'] =  $id_conf;
               
                foreach ($horario as $key => $value) {
                    $b =  (array) $value;
                    $horario_busqueda = DB::table('horarios')
                    ->where('hora_inicio', '=', $b['hora_inicio'])
                    ->where('hora_final', '=', $b['hora_final'])
                    ->get();
                    if(count($horario_busqueda) == 0 ){
                        $id_horario = DB::table('horarios')
                        ->insertGetId($value);
                    
                    }else{
                        $b = (array) $horario_busqueda[0];
                        $id_horario = $b['id'];
                    }
                    $hora = [
                        'id_horarios' => $id_horario,
                        'id_conf' => $id_conf
                    ];
                    DB::table('asignar_horarios')->insert($hora);
                }    
            } elseif (count($busqueda_configuracion) >= 1) {
                $b =  (array) $busqueda_configuracion[0];
                //return $busqueda_configuracion[0];
                $id_conf = $b['id'];
            }
            else{
                return  Response::json([
                    'error' => 'esete es un errore'
                ], 401);
            }
            $configuracion = [
                'id_conf' => $id_conf,
                'id_sala' => $sala['id_sala']
            ];
            $sala['id_conf'] = $id_conf;
            DB::table('asignar_salas')->insert($configuracion);
        } catch (\Throwable $th) {
            return Response::json([
                'error' => $th
            ], 404);
            //new Response(['message' => 'th'], 400);
        }
     

        //$salas = DB::table('salas')->get();
        return $sala;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function show(Request $sala)
    {
        //
        return $sala;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function edit(sala $sala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $sala)
    {
        //
        $consultorio = $request['datos'];
        $horario = $request['horario'];
        //return $horario;
        try {

            DB::table('salas')->where('sala', $consultorio['sala'])
                ->where('id', $consultorio['id'])->update($consultorio);

            DB::table('horarios')
                ->where('sala', '=', $consultorio['sala'])
                ->delete();


            foreach ($horario as $id => $row) {
                //$row['id'] = $id_last;
                //unset($row['sala']);

                DB::table('horarios')
                    ->insert($horario[$id]);
            }
        } catch (\Throwable $th) {
            return $th;
        }
        $salas = DB::table('salas')->get();
        return $salas;
        return $sala;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, int $sala)
    {
        //
        return $id;
    }
    public static function eliminar(Request $item)
    {
        //
        $item = $item['dato'];
        try {
            DB::table('salas')
                ->where('id', '=', $item['id'])
                ->where('sala', '=', $item['sala'])
                ->delete();
        } catch (\Throwable $th) {
            return $th;
        }
        $datos = DB::table('salas')
            ->where('id', '=', $item['id'])->get();
        return $datos;
    }
}
