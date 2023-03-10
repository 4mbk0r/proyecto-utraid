<?php

namespace App\Http\Controllers;

use App\Models\atender;
use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtenderController extends Controller
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
        //$DB::table('atenders')->insert()
        //return $request;
        $equipo = $request['equipo'];
        $ficha = $request['ficha'];
        //return $request;
        $id_ficha = $ficha['id_ficha'];
        $id_sala =  $ficha['id_sala'];
        $id_equipo = $ficha['id_equipo'];
        $r = DB::table('atenders')->where('id_ficha', '=', $id_ficha)->get();

        if (count($r) == 0) {
            try {
                $ficha = $request['ficha'];

                $id_antiguo = $equipo['id_ficha'];
                $id_nuevo = $ficha['id_ficha'];

                $id_ficha = $ficha['id_ficha'];
                //$id_sala =  $ficha['id_sala'];
                $id_equipo = $equipo['id_equipo'];


                $antiguo = (array) DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->first(); //->update(['id_ficha'=>$nuevo] );

                //if id equipó. 
                       
                $nuevo = (array) DB::table('dar_citas')->where('id_ficha', '=', $id_antiguo)->get(); //->update(['id_ficha'=>$antiguo] );
                if(count($nuevo)>0){
                    DB::table('dar_citas')->where('id_ficha', '=', $id_nuevo)->update(['id_persona' => $nuevo['id_persona']]);
    
                }
                //return $antiguo;
                DB::table('dar_citas')->where('id_ficha', '=', $id_antiguo)->update(['id_persona' => $antiguo['id_persona']]);
                

                DB::table('atenders')->insert(['id_ficha' => $id_antiguo, 'id_designado' => $id_equipo]);

                //DB::table('atenders')->insert(['id_ficha' => $id_ficha, 'id_designado' => $id_equipo]);

                //DB::table('atenders')->insert(['id_ficha' => $id_ficha, 'id_designado' => $id_equipo]);
        
                //$s = DB::table('atender_salas')->select('*')->where('id_sala', '=', $id_sala)->get();
                
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
        }
        return 'error';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\atender  $atender
     * @return \Illuminate\Http\Response
     */
    public function show(atender $atender)
    {
        //

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
    public function destroy(atender $atender)
    {
        //
    }
    public static function ver_equipos(Request $request)
    {


        $equipos = DB::table('fichas')
            ->leftJoin('horarios', function ($join) {
                $join->on('horarios.id', '=', 'fichas.id_horario');
            })
            ->leftJoin('designar_equipos', function ($join) {
                $join->on('designar_equipos.fecha', '=', 'fichas.fecha');
                $join->on('designar_equipos.id_sala', '=', 'fichas.id_sala');

                //DB::raw("(designar_equipos.fecha = fichas.fecha and designar_equipos.id_sala = 'fichas'.id_sala)"));
            })
            ->leftJoin('atenders','atenders.id_ficha', '=', 'fichas.id')
            ->leftJoin('equipos', function ($join) {
                $join->on('equipos.id', '=', 'designar_equipos.id_equipo');
            })
            ->select([ '*', 'fichas.id as id_ficha'])
            ->whereNull('id_designado')
            ->where('fichas.fecha', '=', $request['fecha'])
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
            )
            ->get();

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
}
