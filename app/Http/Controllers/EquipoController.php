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
}
