<?php

namespace App\Http\Controllers;

use App\Models\equipo;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $equipo = [
            [
                'equipo' => 'Equipo 1',
                'lista' => []
            ],
            [
                'equipo' => 'Equipo 2',
                'lista' => []
            ],
            [
                'equipo' => 'Equipo 3',
                'lista' => []
            ]

        ];
        $cargo = DB::table('users')
            ->leftJoin('cargos', 'cargos.cargo', '=', 'users.cargo')
            ->where('cargos.servicio', '=', 'true')
            //->where('cargo', '=','recepcionista')
            ->get();
        //  $equipo = json_encode($equipo);
        return inertia('Configuracion/Equipo', [

            'equipo' => $equipo,
            'cargos' => $cargo

        ]);
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
        $total =  [];
        $equipo = (array) $request['equipo'];

        foreach ($equipo as $key => $v) {
            # code...
            $lista = (array) $v['lista'];
            $equipo = $v['equipo'];
            //s return $value['equipo'];
            try {
                $id_equipo =  DB::table('equipos')->insertGetId(
                    ['nombre_equipo' => $equipo]
                );
            } catch (\Throwable $th) {

                return Response::json(['mensaje' => $th->getMessage()], 500);
            }
            $r_equipo = [];
            foreach ($lista as $key => $value) {

                DB::table('asignar_equipos')->insert(
                    [
                        'id_equipo' => $id_equipo,
                        'id_usuario' => $value['id']
                    ]
                );
                array_push($r_equipo, $value);
            }
            array_push($total, [$equipo => $r_equipo, 'id_equipo' => $id_equipo]);
        }


        return $total;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function show(equipo $equipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function edit(equipo $equipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, equipo $equipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(equipo $equipo)
    {
        //
    }
    public static function listar_equipo(string  $fecha)
    {
        //
        $date = date_create(date($fecha), timezone_open('America/La_Paz'));
        $date = date_format($date, 'Y-m-d');
        /*if (Cache::has('citas' . $date)) {
            $cita_fecha =  Cache::get('citas' . $date);
        } else {*/
        /*
        select * from calendarios
        where fecha = '14-02-2023';
        */
        $list_config = DB::table('calendarios')
            ->select('*')
            ->where('fecha', '=', $date)
            ->get();
        if (sizeof($list_config) == 0) {
            /*
            select * from calendariolineals
            where fecha_inicio <= '2023-02-22' and
            fecha_final >= '2023-02-22';
            */

            $list_config = DB::table('calendariolineals')
                ->select('*')
                ->where('fecha_inicio', '<=', $date)
                ->where('fecha_final', '>=', $date)
                //->where('tipo', '=', 'permanente')
                ->get();
        }
        $equipo = [];
        if (sizeof($list_config) > 0) {
            try {


                /*
                select * from designar_equipo_lineals
                left join equipos on equipos.id = designar_equipo_lineals.id_equipo
                left join asignar_equipos on asignar_equipos.id_equipo = equipos.id
                left join users on users.id =  asignar_equipos.id_usuario
                where designar_equipo_lineals.id_conf =  7
                 * 
                 * 
                 */
                //$r_equipo = [];
                $equipo_lista =  [];
                $equipo = DB::table('designar_equipo_lineals')
                    ->select('*')
                    ->leftJoin('equipos', 'equipos.id', '=', 'designar_equipo_lineals.id_equipo')
                    ->where('designar_equipo_lineals.id_conf', '=', $list_config[0]->id_configuracion)
                    ->get();
                    /*->leftJoin('asignar_equipos', 'asignar_equipos.id_equipo', '=', 'equipos.id')
                    ->leftJoin('users', 'users.id', '=', 'asignar_equipos.id_usuario')
                    ->where('designar_equipo_lineals.id_conf', '=', $list_config[0]->id_configuracion)*/
                    /*
                    select * from asignar_equipos
                    left join users on users.id = asignar_equipos.id_usuario
                    where asignar_equipos.id_equipo = 44
                    
                    */
                foreach ($equipo as $key => $value) {
                    $integrantes = DB::table('asignar_equipos')
                    ->select( 'nombre', 'id', 'id_establecimiento','users.ap_paterno', 'users.ap_materno', 'users.ci', 'users.expedido', 'users.email', 'users.celular', 'users.cargo', 'users.username')
                    ->leftJoin('users', 'users.id', '=', 'asignar_equipos.id_usuario')
                    ->leftJoin('contratos', 'users.ci', '=', 'contratos.id_usuario')
                    
                    ->where('asignar_equipos.id_equipo', '=', $value->id_equipo)
                    ->get();
                    array_push($equipo_lista,['equipo'=>$value, 'integrantes'=>  $integrantes ]);
                }
                

                /*
                *          
                */
                $salas_disponibles = [];

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
                    'equipos' => $equipo_lista,
                    //'salas_diponibles' => $salas_disponibles,
                    //'lista_conf' => $list_config,
                    //'casa0' => $list_config[0]->id_configuracion
                ];
            } catch (\Throwable $th) {
                return $th;
            }
            return $resp;
        }
        $resp = [
            'salas' => $equipo,
            //'salas_diponibles' => $salas,

        ];
        return $resp;
    }
}
