<?php

namespace App\Http\Controllers;

use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CChorarioController extends Controller
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
        $h = $request['config_hora'];

        $campoAEliminar = 'tiempo_descanso';

        if (array_key_exists($campoAEliminar, $h)) {
            unset($h[$campoAEliminar]);
        }

        $r = DB::table('conf_salas')
            //->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
            ->where('tiempo_apertura', '=', $h['tiempo_apertura'])
            ->where('tiempo_cierre', '=', $h['tiempo_cierre'])
            ->where('min_promedio_atencion', '=', $h['min_promedio_atencion']);

        // Verificar si 'tiempo_descanso' está presente antes de agregar la condición
        if (isset($h['tiempo_descanso'])) {
            $r->where('tiempo_descanso', '=', $h['tiempo_descanso']);
        } else {
            $r->whereNull('tiempo_descanso');
        }

        $resultado = $r
            //->whereNotNull('id_horario')
            ->get();

        $id_config = null;
        if (count($resultado) == 0) {
            $id_config = DB::table('conf_salas')->insertGetId($h);
        } else {
            if (count($resultado) >= 1) {
                $id_config = $resultado[0]->id;
            } else {
                return $resultado;
            }
        }
        $sala =  $request['sala'];
        $resultado = DB::table('fichas')
            ->select(['*', 'fichas.id as id_ficha'])
            ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
            ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
            ->where('fichas.id_sala', '=', $sala['id_sala'])
            ->where('fichas.fecha', '=', $sala['fecha'])
            ->where('fichas.institucion', '=', $sala['institucion'])
            ->orderBy('horarios.hora_inicio')
            ->get();
        $fecha = $sala['fecha'];
        //return $fecha;
        if (count($resultado) === 0) {
            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get()->first();
            //return $list_config;
            $salas = DB::table('asignar_config_salas')
                ->select(['*', 'conf_salas.id as id_conf_sala'])
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
            $r = DB::table('calendarios')->where('fecha', '=', $fecha)->get();
            if (count($r) == 0) {
                DB::table('calendarios')->insert($i);
            }
            //return $salas;
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
            $resultado = DB::table('fichas')
                ->select(['*', 'fichas.id as id_ficha'])
                ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                ->where('fichas.id_sala', '=', $sala['id_sala'])
                ->where('fichas.fecha', '=', $sala['fecha'])
                ->where('fichas.institucion', '=', $sala['institucion'])
                ->orderBy('horarios.hora_inicio')
                ->get();
        }

        $contarcitas = 0;
        // Acceder a los resultados
        foreach ($resultado as $fila) {
            if ($fila->id_persona) {
                $contarcitas++;
            }
            // Acceder a las columnas, por ejemplo: $fila->nombre_columna
        }
        $horarios = $request['horarios'];
        $conta_nuevo_horaio = count($horarios);
        if ($contarcitas <= $conta_nuevo_horaio) {
            $cambiar = [];
            foreach ($horarios as $fila) {
                $validar = db::table('horarios')
                    ->where('hora_inicio', '=', $fila['hora_inicio'])
                    ->where('hora_final', '=', $fila['hora_final'])
                    ->get();
                if (count($validar) == 0) {
                    $valor = db::table('horarios')->insertGetId([
                        'hora_inicio' => $fila['hora_inicio'],
                        'hora_final' => $fila['hora_final']
                    ]);
                } else {
                    $valor = $validar[0]->id;
                }
                array_push($cambiar, $valor);
                // Acceder a las columnas, por ejemplo: $fila->nombre_columna
            }
            //cambia las fichas actuales
            foreach ($resultado as $fila) {
                if (count($cambiar) > 0) {
                    $id_h = array_shift($cambiar);
                    $update = db::table('fichas')
                        ->where('fichas.id', '=', $fila->id_ficha)
                        ->update(['id_horario' => $id_h, 'id_conf_sala' => $id_config]);
                } else {
                    db::table('fichas')
                        ->where('id', '=', $fila->id_ficha)
                        ->delete();
                }
                // Acceder a las columnas, por ejemplo: $fila->nombre_columna
            }

            if (count($cambiar) > 0) {
                /*$sala['id_sala'])
                ->where('fichas.fecha', '=', $sala['fecha'])
                ->where('fichas.institucion', '=', $sala['institucion'])*/
                foreach ($cambiar as $key => $value) {
                    # code...
                    $id_h = array_shift($cambiar);
                    $update = db::table('fichas')->insert(
                        [
                            'id_horario' => $id_h, 'id_conf_sala' => $id_config,
                            'id_sala' => $sala['id_sala'],  'fecha' => $sala['fecha'],
                            'institucion' => $sala['institucion']
                        ]
                    );
                }
            }
            return $request['horarios'];
        }


        return $request;
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
    public static function crear_horario(Request $request)
    {
        //

    }

    public static function horario_sala(Request $request)
    {
        //
        //return $request;
        $fecha = $request['fecha'];
        $fichas = db::table('fichas')
            ->where('fecha', '=', $fecha)
            ->get();
        //return $fichas;
        if (count($fichas) == 0) {
            $results = DB::table('configuracions')
                ->select('*')
                ->leftJoin('calendariolineals', function ($join) {
                    $join->on('calendariolineals.id_configuracion', '=', 'configuracions.id');
                })
                ->leftJoin('asignar_config_salas', function ($join) {
                    $join->on('asignar_config_salas.id_conf', '=', 'configuracions.id');
                })
                ->leftJoin('conf_salas', function ($join) {
                    $join->on('conf_salas.id', '=', 'asignar_config_salas.id_conf_sala');
                })
                ->leftJoin('salas', function ($join) {
                    $join->on('salas.id', '=', 'asignar_config_salas.id_sala');
                })
                ->where('configuracions.id', 32)
                ->where('salas.id', 1)
                ->where('conf_salas.id', 5)
                ->where('calendariolineals.fecha_inicio', '<=', '2024-03-11')
                ->where('calendariolineals.fecha_final', '>=', '2024-03-11')
                ->get();
            return $results;
        }
        return $request;
    }
    public static function cambiar_horario(Request $request)
    {
        $h = $request['config_hora'];
        //return $h;
        $campoAEliminar = 'tiempo_descanso';

        if (array_key_exists($campoAEliminar, $h)) {
            unset($h[$campoAEliminar]);
        }

        $r = DB::table('conf_salas')
            //->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
            ->where('tiempo_apertura', '=', $h['tiempo_apertura'])
            ->where('tiempo_cierre', '=', $h['tiempo_cierre'])
            ->where('min_promedio_atencion', '=', $h['min_promedio_atencion']);
        //
        // Verificar si 'tiempo_descanso' está presente antes de agregar la condición
        if (isset($h['tiempo_descanso'])) {
            $r->where('tiempo_descanso', '=', $h['tiempo_descanso']);
        } else {
            $r->whereNull('tiempo_descanso');
        }

        $resultado = $r->get();
        //

        //return $resultado;

        $id_config = null;
        if (count($resultado) == 0) {
            $id_config = DB::table('conf_salas')->insertGetId($h);
        } else {
            if (count($resultado) >= 1) {
                $id_config = $resultado[0]->id;
            } else {
                return $resultado;
            }
        }
        $sala =  $request['sala'];
        $resultado = DB::table('fichas')
            ->select(['*', 'fichas.id as id_ficha'])
            ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
            ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
            ->where('fichas.id_sala', '=', $sala['id_sala'])
            ->where('fichas.fecha', '=', $sala['fecha'])
            ->where('fichas.institucion', '=', $sala['institucion'])
            ->orderBy('horarios.hora_inicio')
            ->get();
        $fecha = $sala['fecha'];
        //return $fecha;
        if (count($resultado) === 0) {
            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get()->first();
            //return $list_config;
            $salas = DB::table('asignar_config_salas')
                ->select(['*', 'conf_salas.id as id_conf_sala'])
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
            $r = DB::table('calendarios')->where('fecha', '=', $fecha)->get();
            if (count($r) == 0) {
                DB::table('calendarios')->insert($i);
            }
            //return $salas;
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
            $resultado = DB::table('fichas')
                ->select(['*', 'fichas.id as id_ficha'])
                ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                ->where('fichas.id_sala', '=', $sala['id_sala'])
                ->where('fichas.fecha', '=', $sala['fecha'])
                ->where('fichas.institucion', '=', $sala['institucion'])
                ->orderBy('horarios.hora_inicio')
                ->get();
        }

        $contarcitas = 0;
        // Acceder a los resultados
        foreach ($resultado as $fila) {
            if ($fila->id_persona) {
                $contarcitas++;
            }
            // Acceder a las columnas, por ejemplo: $fila->nombre_columna
        }
        $horarios = $request['horarios'];
        $conta_nuevo_horaio = count($horarios);
        if ($contarcitas <= $conta_nuevo_horaio) {
            $cambiar = [];
            foreach ($horarios as $fila) {
                $validar = db::table('horarios')
                    ->where('hora_inicio', '=', $fila['hora_inicio'])
                    ->where('hora_final', '=', $fila['hora_final'])
                    ->get();
                if (count($validar) == 0) {
                    $valor = db::table('horarios')->insertGetId([
                        'hora_inicio' => $fila['hora_inicio'],
                        'hora_final' => $fila['hora_final']
                    ]);
                } else {
                    $valor = $validar[0]->id;
                }
                array_push($cambiar, $valor);
                // Acceder a las columnas, por ejemplo: $fila->nombre_columna
            }
            //cambia las fichas actuales
            foreach ($resultado as $fila) {
                if (count($cambiar) > 0) {
                    $id_h = array_shift($cambiar);
                    $update = db::table('fichas')
                        ->where('fichas.id', '=', $fila->id_ficha)
                        ->update(['id_horario' => $id_h, 'id_conf_sala' => $id_config]);
                } else {
                    db::table('fichas')
                        ->where('id', '=', $fila->id_ficha)
                        ->delete();
                }
                // Acceder a las columnas, por ejemplo: $fila->nombre_columna
            }

            if (count($cambiar) > 0) {
                /*$sala['id_sala'])
                ->where('fichas.fecha', '=', $sala['fecha'])
                ->where('fichas.institucion', '=', $sala['institucion'])*/
                foreach ($cambiar as $key => $value) {
                    # code...
                    $id_h = array_shift($cambiar);
                    $update = db::table('fichas')->insert(
                        [
                            'id_horario' => $id_h, 'id_conf_sala' => $id_config,
                            'id_sala' => $sala['id_sala'],  'fecha' => $sala['fecha'],
                            'institucion' => $sala['institucion']
                        ]
                    );
                }
            }
            return $request['horarios'];
        }


        return $request;
    }
    public static function nueva_sala(Request $request)
    {

        //return $request;
        $lugar = $request['datos'];
        $fecha = $request['fecha'];
        $nueva_sala = $request['nueva_sala'];
        $horario = $request['horarios'];
        $fichas = DB::table('fichas')
            ->leftJoin('salas', 'fichas.id_sala', '=', 'salas.id')
            ->where('fecha', '=', $fecha)
            ->get();
        //return $fichas;
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

        $salas2 =  DB::table('salas')
            ->select('salas.*')
            ->leftJoin('fichas', function ($join) use ($fecha) {
                $join->on('salas.id', '=', 'fichas.id_sala')
                    ->where('fichas.fecha', '=', $fecha);
            })
            ->whereNull('fichas.id_sala')
            ->where('salas.descripcion', 'like', 'salaauxiliar%')
            ->first();
        //return $salas2;
        if (empty($salas2)) {
            $salas2 = DB::table('salas')
                ->where('descripcion', 'like', 'salaauxiliar%')
                ->get();
            $id = DB::table('salas')->insertGetId([
                'descripcion' => 'salaauxiliar ' . count($salas2),
                'institucion' => $lugar
            ]);
            $salas2 = DB::table('salas')
                ->where('id', '=', $id)
                ->first();
            $salas2->id_sala = $id;
        }

        $conf_opcion = DB::table('conf_salas')
            ->where('tiempo_apertura', '=', $nueva_sala['tiempo_apertura'])
            ->where('tiempo_cierre', '=', $nueva_sala['tiempo_cierre'])
            ->where('min_promedio_atencion', '=', $nueva_sala['min_promedio_atencion'])
            ->whereNull('tiempo_descanso')
            ->first();
        if (empty($conf_opcion)) {
            $conf_opcion = DB::table('conf_salas')
                ->insertGetId([
                    'tiempo_apertura' => $nueva_sala['tiempo_apertura'],
                    'tiempo_cierre' => $nueva_sala['tiempo_cierre'],
                    'min_promedio_atencion' => $nueva_sala['min_promedio_atencion']
                ]);
            $conf_opcion = DB::table('conf_salas')
                ->where('id', '=', $conf_opcion)
                ->get();
        }

        $h_actual = DB::table('conf_salas')
            ->select('*')
            ->leftJoin('asignar_horarios', function ($join) {
                $join->on('asignar_horarios.id_conf_sala', '=', 'conf_salas.id');
            })
            ->leftJoin('horarios', function ($join) {
                $join->on('horarios.id', '=', 'asignar_horarios.id_horario');
            })
            ->where('conf_salas.id', $conf_opcion->id)
            ->get();
        //return $horario;
        if (count($h_actual) == 0) {
            foreach ($horario as $key => $value) {
                # code...
                $hora = DB::table('horarios')
                    ->where('hora_inicio', $value['hora_inicio'])
                    ->where('hora_final', $value['hora_final'])
                    ->first();
                if (empty($hora)) {
                    $id_hora = DB::table('horarios')->insertGetId(
                        [
                            'hora_inicio' => $value['hora_inicio'],
                            'hora_final' => $value['hora_final']
                        ]
                    );
                } else {
                    $id_hora = $hora->id;
                }

                DB::table('asignar_horarios')
                    ->insert([
                        'id_horario' => $id_hora,
                        'id_conf_sala' => $conf_opcion->id
                    ]);
            }
            $h_actual = DB::table('conf_salas')
                ->select('*')
                ->leftJoin('asignar_horarios', function ($join) {
                    $join->on('asignar_horarios.id_conf_sala', '=', 'conf_salas.id');
                })
                ->leftJoin('horarios', function ($join) {
                    $join->on('horarios.id', '=', 'asignar_horarios.id_horario');
                })
                ->where('conf_salas.id', $conf_opcion->id)
                ->get();
        }
        //return $salas2->id_sala;
        try {
            //code...
            foreach ($h_actual as $key => $value) {

                $i = DB::table('fichas')
                    ->where('fecha', '=', $fecha)
                    ->where('id_sala', '=', $salas2->id)
                    ->where('id_conf_sala', '=', $conf_opcion->id)
                    ->where('institucion', '=', $lugar)
                    ->where('id_horario', '=', $value->id)
                    ->get();

                if (count($i) == 0) {

                    DB::table('fichas')
                        ->insert(
                            [
                                'fecha' => $fecha,
                                'id_sala' => $salas2->id,
                                'id_conf_sala' => $conf_opcion->id,
                                'institucion' => $lugar,
                                'id_horario' => $value->id
                            ]
                        );
                }
            }
        } catch (\Throwable $th) {
            return  $th;
        }
        # code...
        return 'ok';


        DB::table('conf_salas')
            ->leftJoin('fichas', 'salas.id', '=', 'fichas.id_sala')
            ->where('descripcion', 'like', 'salaauxiliar%')
            ->whereNotNull('salas.id')
            ->first();

        $conf_opcion = DB::table('conf_salas')
            ->where('tiempo_apertura', '=', $nueva_sala['hora_inicio'])
            ->where('tiempo_cierre', '=', $nueva_sala['hora_final'])
            ->where('min_promedio_atencion', '=', $nueva_sala['min_promedio_atencion'])
            ->whereNull('tiempo_descanso')
            ->get();

        DB::table('fichas')->insert([
            'institucion' => $lugar,
            'id_sala' => $salas2[0]->id,
            'id_horario' => $request['id_horario'],
            'id_conf_sala' => $conf_opcion->id,
            'fecha' => $fecha
        ]);


        return $request;
    }
    public static function eliminar_sala(Request $request)
    {
        $fichas = DB::table('fichas')
            ->select('fichas.*')
            ->where('id_sala', '=', $request['id_sala'])
            ->where('fecha', '=', $request['fecha'])
            ->get();
        $citas = DB::table('fichas')
        ->select('fichas.*')
        ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
        ->where('id_sala', '=', $request['id_sala'])
        ->where('fecha', '=', $request['fecha'])
        ->whereNotNull('dar_citas.id_ficha')
        ->get();
        if (count($fichas) > 0 && count($citas) === 0) {
            $fichas = DB::table('fichas')
                ->where('id_sala', '=', $request['id_sala'])
                ->where('fecha', '=', $request['fecha'])
                ->delete();
        }
        elseif(count($citas) > 0){
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar la sala',
                'error' => 'SALA CON CITA',
            ], 400);
        }
        return $request;
    }
}
