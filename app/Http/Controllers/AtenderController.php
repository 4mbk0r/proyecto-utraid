<?php

namespace App\Http\Controllers;

use App\Events\AtenderEvent;
use App\Events\DataUpdated;
use App\Events\PrivateMessage;
use App\Events\ProductoActualizado;
use App\Events\SaludoUsuario;
use App\Events\SomeEvent;
use App\Models\atender;
use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use SocketIO\Client;

class AtenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    protected $client;
    public function index()
    {
        //$this->client = new Client('http://localhost:3000');
        // Consulta SQL original:
        /* 
         select * from atenders
        LEFT JOIN fichas ON fichas.id = atenders.id_ficha
		left join salas ON salas.id = fichas.id_sala
		left join horarios ON horarios.id = fichas.id_horario
		LEFT JOIN dar_citas ON dar_citas.id_ficha = fichas.id
        LEFT JOIN personas ON personas.id = dar_citas.id_persona
        left join equipos ON equipos.id = atenders.id_designado
        left join asignar_equipos ON asignar_equipos.id_equipo = equipos.id 
        left join users ON users.id = asignar_equipos.id_usuario
        where users.username = '6011483'
        and fichas.fecha = '2023-09-05'
        */
        // Obtener el nombre de usuario del usuario autenticado
        //event(new SomeEvent('holllla'));
        $username = auth()->user()->username;

        // Obtener la fecha actual en formato 'Y-m-d'
        $hoy = Carbon::now()->toDateString();

        $resultados = DB::table('atenders')
            ->select(
                'atenders.*',
                'fichas.*',
                'horarios.*',
                'salas.*',
                'dar_citas.*',
                'personas.*',
                'equipos.*',
                'asignar_equipos.*',
            )
            ->leftJoin('fichas', 'fichas.id', '=', 'atenders.id_ficha')
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
            ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
            ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
            ->leftJoin('equipos', 'equipos.id', '=', 'atenders.id_designado')
            ->leftJoin('asignar_equipos', 'asignar_equipos.id_equipo', '=', 'equipos.id')
            ->leftJoin('users', 'users.id', '=', 'asignar_equipos.id_usuario')
            ->where('users.username', $username)
            ->where('fichas.fecha', $hoy)
            ->get();

        //event(new SaludoUsuario($username));

        return inertia('Atencion/Atencion', [
            'atenciones1' => $resultados,
            'hoy' => $hoy,
            'fecha_actual' => $hoy,
        ]);
        //event(new ProductoActualizado('JISISISIS'));
        return $resultados;
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
        $mensaje = "Alguien ha accedido a la ruta 'mandarmensaje'";

        // Actualizar el mensaje en el caché
        //Cache::put('mensaje_actualizado', $mensaje);
        //$DB::table('atenders')->insert()
        //return $request;
        //return $ficha['fecha'];
        //return $request;
        $ficha = $request['ficha'];
        $id_ficha = $ficha['id_ficha'];

        $r = DB::table('atenders')->where('id_ficha', '=', $id_ficha)->get();
        if (count($r) == 0) {
            try {
                $ficha = $request['ficha'];
                $equipo = $request['equipo'];

                $id_ficha = $ficha['id_ficha'];
                //$id_sala =  $ficha['id_sala'];
                $id_medico = $equipo;

                DB::table('atenders')->insert(['id_ficha' => $id_ficha, 'id_designado' => $id_medico]);

                /* 
                $antiguo = (array) DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->first(); //->update(['id_ficha'=>$nuevo] );

                //if id equipó. 

                $nuevo = (array) DB::table('dar_citas')->where('id_ficha', '=', $id_antiguo)->first(); //->update(['id_ficha'=>$antiguo] );
                //return $nuevo['id_persona'];

                if (isset($nuevo['id_persona'])) {
                    DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->update(['id_persona' => $nuevo['id_persona']]);
                    DB::table('dar_citas')->where('id_ficha', '=', $id_antiguo)->update(['id_persona' => $antiguo['id_persona']]);
                } else {

                    DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->delete();
                    DB::table('dar_citas')->insert(['id_persona' => $antiguo['id_persona'], 'id_ficha' => $id_antiguo]);
                }

                //return $antiguo;

                DB::table('atenders')->where('id_ficha', '=', $id_nuevo)->delete();

                DB::table('atenders')->insert(['id_ficha' => $id_antiguo, 'id_designado' => $id_equipo]);

                // SQL original: select * from users
                // left join asignar_equipos ON asignar_equipos.id_usuario = users.id
                // where id_equipo = '4'
                
                
                $usuarios = User::select('users.*')
                    ->leftJoin('asignar_equipos', 'asignar_equipos.id_usuario', '=', 'users.id')
                    ->where('asignar_equipos.id_equipo', '=', $id_equipo)
                    ->get();

                foreach ($usuarios as $user) {
                    //event(new PrivateMessage($user));
                    event(new AtenderEvent($user, $ficha['fecha']));
            
                    //echo 'ID de Usuario: ' . $user->id . '<br>';
                }



                //DB::table('atenders')->insert(['id_ficha' => $id_ficha, 'id_designado' => $id_equipo]);

                //DB::table('atenders')->insert(['id_ficha' => $id_ficha, 'id_designado' => $id_equipo]);

                //$s = DB::table('atender_salas')->select('*')->where('id_sala', '=', $id_sala)->get();
                */
            } catch (\Throwable $th) {
                //throw $th;
                return $th;
            }


            $horario = DB::table('fichas')
                ->select(['fichas.*', 'horarios.*', 'dar_citas.*', 'personas.*', 'atenders.id_designado'])
                ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
                ->leftJoin('conf_salas', 'conf_salas.id', '=', 'fichas.id_sala')
                ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
                ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
                ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
                //->rigthJoin('atenders', 'atenders.id_ficha', 'fichas.id')
                ->leftJoin('atenders', 'fichas.id', '=', 'atenders.id_ficha')
                ->where('fecha', '=', $ficha['fecha'])
                ->where('fichas.id', '=', $ficha['id_ficha'])
                ->orderBy('salas.descripcion', 'asc')

                //->groupBy('id_sala', 'salas.descripcion')
                ->get();
            return $horario;
        } else {
            /*
            $ficha = $request['ficha'];

            $id_antiguo = $equipo['id_ficha'];
            $id_nuevo = $ficha['id_ficha'];

            $id_ficha = $ficha['id_ficha'];
            //$id_sala =  $ficha['id_sala'];
            $id_equipo = $equipo['id_equipo'];
            $antiguo = (array) DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->first(); //->update(['id_ficha'=>$nuevo] );

            DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->delete();
            DB::table('atenders')->where('id_ficha', '=', $id_nuevo)->delete();
            DB::table('atenders')->where('id_ficha', '=', $id_antiguo)->delete();
            DB::table('dar_citas')->insert(['id_persona' => $antiguo['id_persona'], 'id_ficha' => $id_antiguo]);
            DB::table('atenders')->insert(['id_ficha' => $id_antiguo, 'id_designado' => $id_equipo]);
            */
        }
        return 'error';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\atender  $atender
     * @return \Illuminate\Http\Response
     */
    public function show(String $atender)
    {

        //
        // Consulta SQL original:
        /* 
         select * from atenders
        LEFT JOIN fichas ON fichas.id = atenders.id_ficha
		left join salas ON salas.id = fichas.id_sala
		left join horarios ON horarios.id = fichas.id_horario
		LEFT JOIN dar_citas ON dar_citas.id_ficha = fichas.id
        LEFT JOIN personas ON personas.id = dar_citas.id_persona
        left join equipos ON equipos.id = atenders.id_designado
        left join asignar_equipos ON asignar_equipos.id_equipo = equipos.id 
        left join users ON users.id = asignar_equipos.id_usuario
        where users.username = '6011483'
        and fichas.fecha = '2023-09-05'
        */
        // Obtener el nombre de usuario del usuario autenticado
        $username = auth()->user()->username;

        // Obtener la fecha actual en formato 'Y-m-d'
        $hoy = Carbon::now()->toDateString();

        $resultados = DB::table('atenders')
            ->select(
                'atenders.*',
                'fichas.*',
                'salas.*',
                'horarios.*',
                'dar_citas.*',
                'personas.*',
                'equipos.*',
                'asignar_equipos.*',
            )
            ->leftJoin('fichas', 'fichas.id', '=', 'atenders.id_ficha')
            ->leftJoin('salas', 'salas.id', '=', 'fichas.id_sala')
            ->leftJoin('horarios', 'horarios.id', '=', 'fichas.id_horario')
            ->leftJoin('dar_citas', 'dar_citas.id_ficha', '=', 'fichas.id')
            ->leftJoin('personas', 'personas.id', '=', 'dar_citas.id_persona')
            ->leftJoin('equipos', 'equipos.id', '=', 'atenders.id_designado')
            ->leftJoin('asignar_equipos', 'asignar_equipos.id_equipo', '=', 'equipos.id')
            ->leftJoin('users', 'users.id', '=', 'asignar_equipos.id_usuario')
            ->where('users.username', $username)
            ->where('fichas.fecha', $atender)
            ->get();

        return inertia('Atencion/Atencion', [
            'atenciones1' => $resultados,
            'hoy' => $hoy,
            'fecha_actual' => $atender,
        ]);
        return $resultados;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\atender  $atender
     * @return \Illuminate\Http\Response
     */
    public function edit(atender $atender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\atender  $atender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, atender $atender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\atender  $atender
     * @return \Illuminate\Http\Response
     */
    public static function destroy(int $id_ficha)
    {
        //
        $mensaje = "Alguien ha accedido a la ruta 'mandarmensaje'";

        // Actualizar el mensaje en el caché
        //Cache::put('mensaje_actualizado', $mensaje);

        $selecion = DB::table('atenders')
            ->leftJoin('fichas', 'fichas.id', '=', 'atenders.id_ficha')
            ->where('id_ficha', '=', $id_ficha)
            ->first();

        DB::table('atenders')->where('id_ficha', '=', $id_ficha)->delete();


        $usuarios = User::select('users.*')
            ->leftJoin('asignar_equipos', 'asignar_equipos.id_usuario', '=', 'users.id')
            ->where('asignar_equipos.id_equipo', '=', $selecion->id_designado)
            ->get();

        foreach ($usuarios as $user) {
            //event(new PrivateMessage($user));
            event(new AtenderEvent($user, $selecion->fecha));
            //event(new PrivateMessage($user));
            //echo 'ID de Usuario: ' . $user->id . '<br>';
        }
        return $id_ficha;
        //DB::table('atenders')->where('id_ficha', '=', $id_nuevo)->delete();

    }
    public static function ver_equipos(Request $request)
    {


        $today = date("Y-m-d");



        if ($request['id_ficha'] == null) return;

        if ($request['id_designado'] != null) {


            $medico = DB::table('users')
                ->leftJoin('atenders', 'atenders.id_designado', '=', 'users.id')
                ->where('atenders.id_ficha', '=', $request['id_ficha'])
                ->select('users.*')
                ->get();


            /*DB::table('fichas')
                ->leftJoin('atenders', 'atenders.id_ficha', '=', 'fichas.id')
                ->leftJoin('users', 'users.id', '=', 'atenders.id_designado')
                ->where('atenders.id_designado', '=', $request['id_designado'])
                ->where('atenders.id_ficha', '=', $request['id_ficha'])
                ->get();*/
            //return $equipos;
            //array_push($r_equipo, $medico);
            $r = [
                'seleccion' => true,
                'equipo' => $medico
            ];
            return $r;
        }
        //return $request['fecha'].'='. $today; 
        if ($request['fecha'] != $today) {
            return  $r = [
                'seleccion' => false,

                'equipo' => []
            ];
        }
        try {
            $cargo = 'MEDICO GENERAL';
            $fecha = $request['fecha'];
            $horaInicio = $request['hora_inicio'];
            $horaFinal = $request['hora_final'];

            $medicosSinAtender = DB::table('users')
                ->where('cargo', $cargo)
                ->whereRaw("NOT EXISTS (
                SELECT 1
                FROM cargos
                LEFT JOIN atenders ON atenders.id_designado = users.id
                LEFT JOIN fichas ON fichas.id = atenders.id_ficha AND fichas.fecha = '$fecha'
                LEFT JOIN horarios ON horarios.id = fichas.id_horario
                WHERE cargo = users.cargo
                AND atenders.id_designado IS NOT NULL
                AND horarios.hora_inicio >= '$horaInicio'
                AND horarios.hora_final <= '$horaFinal'
                AND cargos.servicio = true
            )")
                ->get();
        } catch (Exception $e) {
            return $e;
        }


        $r = [
            'seleccion' => false,
            'equipo' => $medicosSinAtender
        ];
        return $r;
        /*$equipo = $request['equipo'];
        $ficha = $request['ficha'];
        
        $id_antiguo = $equipo['id_ficha'];
        $id_nuevo = $ficha['id_ficha'];
        
        $id_ficha = $ficha['id_ficha'];
        //$id_sala =  $ficha['id_sala'];
        $id_equipo = $ficha['id_equipo'];

        
        /*$antiguo =(array) DB::table('dar_citas')->where('id_ficha', '=', $id_antiguo)->get()[0];//->update(['id_ficha'=>$nuevo] );
        
        $nuevo =(array) DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->get()[0];//->update(['id_ficha'=>$antiguo] );
       
        
        DB::table('dar_citas')->where('id_ficha', '=', $id_antiguo)->update(['id_persona' => $antiguo['id_persona']]);
        DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->update(['id_persona' => $nuevo['id_persona']]);


        DB::table('atenders')->insert(['id_ficha' => $id_ficha, 'id_designado' => $id_equipo]);
          */
        //return $request;
        //$request;
        /*$equipos = DB::table('fichas')
            ->leftJoin('atenders', 'atenders.id_ficha', '=', 'fichas.id')
            ->leftJoin('equipos', 'equipos.id', '=', 'atenders.id_designado')
            ->where('fichas.id', '=', $request['id_ficha'])
            ->where('id_designado', '!=', null)
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
        $seleccion =  true;
        if (count($equipos) == 0) {

            $equipo = $request['equipo'];
            $ficha = $request['ficha'];

            $id_antiguo = $equipo['id_ficha'];
            $id_nuevo = $ficha['id_ficha'];

            $id_ficha = $ficha['id_ficha'];
            //$id_sala =  $ficha['id_sala'];
            $id_equipo = $ficha['id_equipo'];


            $antiguo = (array) DB::table('dar_citas')->where('id_ficha', '=', $id_antiguo)->get()[0]; //->update(['id_ficha'=>$nuevo] );

            $nuevo = (array) DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->get()[0]; //->update(['id_ficha'=>$antiguo] );


            DB::table('dar_citas')->where('id_ficha', '=', $id_antiguo)->update(['id_persona' => $antiguo['id_persona']]);
            DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->update(['id_persona' => $nuevo['id_persona']]);


            DB::table('atenders')->insert(['id_ficha' => $id_ficha, 'id_designado' => $id_equipo]);

            return $request;
            */
        /*$equipos =

                DB::table("fichas")
                ->select('*')
                ->join("atenders", function ($join)  use ($request) {
                    $join->on("atenders.id_ficha", "=", "fichas.id")
                        //->where("fichas.id_horario", "=",  $request['id_horario'])
                        ->where("fichas.fecha", "=",  $request['fecha']);
                })
                ->join("horarios", function ($join) use ($request) {
                    $join->on("horarios.id", "=", "fichas.id_horario")
                        ->where(
                            function ($query) use ($request) {
                                return $query
                                    ->where(
                                        function ($query) use ($request) {
                                            return $query
                                                ->where('horarios.hora_inicio', '>=', $request['hora_inicio'])
                                                ->where('horarios.hora_inicio', '<', $request['hora_final']);
                                        }
                                    )
                                    ->orwhere(
                                        function ($query) use ($request) {
                                            return $query
                                                ->where('horarios.hora_final', '>', $request['hora_inicio'])
                                                ->where('horarios.hora_final', '<', $request['hora_final']);
                                        }
                                    );  

                                //->where('that_too', '=', 1);
                            }
                        );
                })
                ->rightJoin("equipos", function ($join) {
                    $join->on("equipos.id", "=", "atenders.id_designado");
                })
                ->join("designar_equipos", function ($join) {
                    $join->on("designar_equipos.id_equipo", "=", "equipos.id");
                })
                ->whereNull("fichas.id")
                ->where("designar_equipos.fecha", "=",  $request['fecha'])
                ->get();
            //return $equipos;



            $seleccion = false;
            $r_equipo = [];
            foreach ($equipos as $key => $value) {
                $lista_equipos = DB::table('equipos')
                    ->select('*')
                    ->leftJoin('asignar_equipos', 'asignar_equipos.id_equipo', '=', 'equipos.id')
                    ->leftJoin('users', 'users.id', '=', 'asignar_equipos.id_usuario')
                    ->where('equipos.id', '=', $value->id_equipo)
                    ->get();
                $a =  [
                    'equipo' => $value,
                    'lista' => $lista_equipos
                ];
                array_push($r_equipo, $a);
            }
            $r = [
            'seleccion' => $seleccion,
            'equipo' => $r_equipo
        ];
            /*$equipos = DB::table('equipos')
                ->select('*')
                ->leftJoin('designar_equipo_lineals', 'designar_equipo_lineals.id_equipo', '=', 'equipos.id')
                ->where('designar_equipo_lineals.id_conf', '=', $list_config2[0]->id_configuracion)
                ->get();*/
        /*}

        $r = [
            'seleccion' => $seleccion,
            'equipo' => $r_equipo
        ];

        return $r;*/
        //->where('fichas.fecha','=', $fecha);
    }
    public static function sala_equipos(Request $request)
    {

        //return $request;
        $datos =  $request;
        $horario = DB::table('fichas')->select('*')
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
        if (count($horario) == 0) {
            try {
                $query = 
                DB::table('configuracions')
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
                ->where('configuracions.id', $request['id_conf'])
                ->where('salas.id', $request['id_sala'])
                ->where('conf_salas.id', $request['id_conf_sala'])
                ->where('calendariolineals.fecha_inicio', '<=', $request['fecha'])
                ->where('calendariolineals.fecha_final', '>=', $request['fecha'])
                ->get();
    
                
            } catch (\Throwable $th) {
                return $th;
            }
            
            return $query;
        } else {
            $results = DB::table('fichas')
                ->select('*')
                ->leftJoin('salas', function ($join) {
                    $join->on('salas.id', '=', 'fichas.id_sala');
                })
                ->leftJoin('conf_salas', function ($join) {
                    $join->on('conf_salas.id', '=', 'fichas.id_conf_sala');
                })
                ->where('fichas.fecha', '=', $request['fecha'])
                ->where('fichas.institucion', '=', $request['institucion'])
                ->where('fichas.id_sala', '=', $request['id'])
                ->get();
            return $results;
        }
        //return $is_calendar;
    }
}
