<?php

namespace App\Http\Controllers;

use App\Models\dar_cita;
use App\Http\Controllers\Controller;
use App\Models\Ficha;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Audit;
use OwenIt\Auditing\Facades\Auditor;

class DarCitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        /*
        select *
        from fichas
        left join dar_citas ON dar_citas.id_ficha = fichas.id
        left join  personas ON personas.id = dar_citas.id_persona          
         */

        /*
        $cargo = DB::table('fichas')
            ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
            ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')


            //->where('cargos.servicio', '=', 'true')
            //->where('cargo', '=','recepcionista')
            ->get();
        return $cargo;*/
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
            return new Response(['message' => $th], 400);
        }


        return $validar;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\dar_cita  $dar_cita
     * @return \Illuminate\Http\Response
     */
    public function show(string $fecha)
    {
        //
        //$fecha = $request['fecha'];
        $validar =  DB::table('fichas')
            ->select('id_sala', 'salas.descripcion')
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->where('fecha', '=', $fecha)
            ->groupBy('id_sala', 'salas.descripcion')
            ->get();
        if (count($validar) == 0) {
            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                //->where('tipo', '=', 'permanente')
                ->get()[0];
            $salas = DB::table('asignar_config_salas')
                ->select('*')
                ->leftJoin('salas', 'salas.id', '=', 'asignar_config_salas.id_sala')
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'asignar_config_salas.id_conf_sala')
                ->leftJoin('asignar_horarios', 'asignar_horarios.id_conf_sala', '=', 'conf_salas.id')
                ->leftJoin('horarios', 'horarios.id', '=', 'asignar_horarios.id_horarios')
                ->where('id_conf', '=', $list_config->id_configuracion)
                ->get();

            foreach ($salas as $key => $value) {
                # code...
                $nuevo = [
                    'id_sala' => $value->id_sala,
                    'id_horario' => $value->id_horarios,
                    'id_conf_sala' => $value->id_conf_sala,
                    'fecha' => $fecha,
                    'codigo' =>  $value->institucion,

                ];
                DB::table('fichas')->insert($nuevo);
            }
        }
        $horarios =  [];
        foreach ($validar as $key => $value) {
            # code...
            $horario = DB::table('fichas')
                ->select('*', 'evaluacions.id_persona as id_persona ')
                ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'fichas.id_sala')
                ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
                ->leftJoin('evaluacions', 'personas.id', '=', 'evaluacions.id_persona')

                ->where('fecha', '=', $fecha)
                ->where('fichas.id_sala', '=', $value->id_sala)
                //->groupBy('id_sala', 'salas.descripcion')
                ->get();
            array_push($horarios, (array) $horario);
        }
        return $horarios;
    }

    /**
     * Show the form    for editing the specified resource.
     *
     * @param  \App\Models\dar_cita  $dar_cita
     * @return \Illuminate\Http\Response
     */
    public function edit(dar_cita $dar_cita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\dar_cita  $dar_cita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, dar_cita $dar_cita)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\dar_cita  $dar_cita
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $dar_cita)
    {
        //  

        // Obtén el registro que vas a eliminar (opcional, pero útil para auditoría)
        $registroAEliminar = DB::table('dar_citas')
            ->where('id_ficha', '=', $dar_cita)
            ->first(); // Puedes usar ->get() si esperas varios registros
        //return 'casasasa'.$registroAEliminar->id_ficha;
        if ($registroAEliminar) {
            // Realiza la eliminación
            try {

                //$arregloAsociativo = json_decode($registroAEliminar, true);

                // Convertir el arreglo asociativo nuevamente a una cadena de texto
                $textoConvertido = json_encode($registroAEliminar);
                $textoConvertido = (string)$textoConvertido;
                $eventoAuditoria = [
                    'user_id' => auth()->user() ? auth()->user()->id : null,
                    'user_type' => auth()->user() ? get_class(auth()->user()) : null,
                    'event' => 'delete impresion',
                    'auditable_id' => $registroAEliminar ? $registroAEliminar->id_ficha : null,
                    'auditable_type' => $registroAEliminar ? get_class($registroAEliminar) : null,
                    'tags' => 'se eliminó una ficha de la base de datos',
                    'new_values' => $textoConvertido, // Agregar la fecha y hora actual
                    'updated_at' => now()->toDateTimeString(), 
                    // Otros detalles del evento de auditoría, si es necesario
                ];
                

                DB::table('audits')->insert($eventoAuditoria);
                //Auditor::audit()->audit($eventoAuditoria);
                DB::table('dar_citas')
                    ->where('id_ficha', '=', $dar_cita)
                    ->delete();
            } catch (QueryException $e) {
                return $e;
                // Maneja la excepción aquí, por ejemplo, puedes registrarla o mostrar un mensaje de error.
                // Ten en cuenta que las excepciones pueden ocurrir debido a problemas de la base de datos, como restricciones de clave externa, por lo que es importante manejarlas adecuadamente.
                // También puedes revertir la transacción si es relevante para tu aplicación.
            }
            // Registra la eliminación en Laravel Audit
            // Esto registra la eliminación

            // Opcionalmente, también puedes registrar manualmente el evento de eliminación
            // Auditor::audit()->log('Eliminación de registro', 'destroy', $registroAEliminar);
        } else {
            // El registro no existe, maneja este caso según tus necesidades
        }
    }
    public static function cambiar_cita(Request $request)
    {
        //
        //return $request;



        $ficha1 =  $request['ficha1'];
        $ficha2 =  $request['ficha2'];

        DB::table('dar_citas')
            ->where('id_ficha', '=', $ficha2['id_ficha'])
            ->delete();


        //return $request;   
        if (isset($ficha1['id_persona'])) {
            DB::table('dar_citas')
                ->where('id_ficha', '=', $ficha1['id_ficha'])
                ->delete();
            DB::table('dar_citas')
                ->updateOrInsert(['id_ficha' => $ficha2['id_ficha'], 'id_persona' => $ficha1['id_persona']]);
        }
        if (isset($ficha2['id_persona'])) {
            DB::table('dar_citas')
                ->updateOrInsert(['id_ficha' => $ficha1['id_ficha'], 'id_persona' => $ficha2['id_persona']]);
        }

        return $request;


        return $ficha1;
    }
    public static function get_cita(Request $request)
    {

        $paciente =  (array) $request['paciente'];
        //return $paciente;

        try {
            $citas = DB::table("dar_citas")
                ->select(['*', 'evaluacions.id_persona as id_persona', 'personas.id as id', 'fichas.id  as id_ficha'])
                ->leftJoin("fichas", function ($join) {
                    $join->on("fichas.id", "=", "dar_citas.id_ficha");
                })
                ->leftJoin("personas", function ($join) use ($paciente) {
                    $join->on("personas.id", "=", "dar_citas.id_persona");
                })
                ->leftJoin("evaluacions", function ($join) use ($paciente) {
                    $join->on("personas.id", "=", "evaluacions.id_persona");
                })

                ->leftJoin("atenders", function ($join) use ($paciente) {
                    $join->on("atenders.id_ficha", "=", "fichas.id");
                })
                ->leftJoin("salas", function ($join) use ($paciente) {
                    $join->on("salas.id", "=", "fichas.id_sala");
                })
                ->leftJoin("viajes", function ($join) use ($paciente) {
                    $join->on("viajes.id_sala", "=", "salas.id");
                })
                ->leftJoin("municipios", function ($join) use ($paciente) {
                    $join->on("municipios.id", "=", "viajes.id_municipio");
                })
                ->leftJoin("horarios", function ($join) use ($paciente) {
                    $join->on("horarios.id", "=", "fichas.id_horario");
                })
                ->leftJoin("calendarios", function ($join) use ($paciente) {
                    $join->on("calendarios.fecha", "=", "fichas.fecha");
                })
                ->leftJoin("institucions", function ($join) use ($paciente) {
                    $join->on("institucions.codigo", "=", "calendarios.codigo");
                })

                /*
                    left join viajes ON viajes.id_sala = salas.id
                    left join municipios ON municipios.id = viajes.id_municipio
                 */

                /*
                left join salas ON salas.id = fichas.id_sala
left join horarios ON horarios.id = fichas.id_horario
                
                */
                //->where("fichas.fecha", "<", DB::raw("current_date"))
                //->where("fichas.fecha", "<=", DB::raw("current_date"))
                ->where("dar_citas.id_persona", "=", $paciente['id'])
                ->get();


            $registro = DB::table("evaluacions")
                ->leftJoin("personas", function ($join) {
                    $join->on("personas.id", "=", 'evaluacions.id_persona');
                })
                ->where("evaluacions.id_persona", "=", $paciente['id'])
                ->get();

            return [
                "cita" => $citas,
                "registro" => $registro
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }
    }
}
