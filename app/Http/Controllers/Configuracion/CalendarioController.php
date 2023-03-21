<?php

namespace App\Http\Controllers\Configuracion;

use App\Models\Calendario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;

class CalendarioController extends Controller
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
     * @param  \App\Models\Calendario  $calendario
     * @return \Illuminate\Http\Response
     */
    public function show(Calendario $calendario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Calendario  $calendario
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendario $calendario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendario  $calendario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendario $calendario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendario  $calendario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendario $calendario)
    {
        //
    }
    public static function verificar_fecha(String $fecha)
    {
        try {
            $fecha_valida = DB::table('calendarios')
                ->select('*')
                //->leftJoin('configuracions', 'configuracions.id', '=', 'cita_tiene_configuracions.id')
                ->where('fecha', '>=', date($fecha))
                ->where('clase', '=', 'atencion')
                ->get();
            $verificar = false;
            $n = count($fecha_valida);
            if ($n == 0) {
                $verificar = true;
            }
            $resp = [
                'lista_fechas' => $fecha_valida,
                'verificar' => $verificar,
                'n' => $n
            ];
        } catch (\Throwable $th) {
            return response()->json(['error' => 'error al insertar datos'], 409);
        }

        return $resp;
    }
    public static function fechas_vigentes()
    {


        $sql = DB::table("calendarios")
            ->select(["fichas.fecha", DB::raw("count(dar_citas.id_ficha) as nro_citas")])
            ->leftJoin("fichas", function ($join) {
                $join->on("fichas.fecha", "=", "calendarios.fecha");
            })
            ->leftJoin("dar_citas", function ($join) {
                $join->on("dar_citas.id_ficha", "=", "fichas.id");
            })
            ->where('calendarios.fecha','>=',date('d-m-Y'))

            ->groupBy("fichas.fecha")
            ->get();
        return $sql;

        //return $resp;
    }
    public static function fechas_feriados()
    {


        $sql = DB::table("calendarios")
            ->where('calendarios.fecha','>=',date('d-m-Y'))
            ->where('atencion', '=', 'feriado')
            ->get();
        return $sql;

        //return $resp;
    }
    public static function add_feriados($request)
    {

        //return $request;
        if(DB::table("calendarios")->where('fecha', '=', $request['feriado']['fecha'])->exists()){
            return new Response(['message' => 'Datos dupliacados' ], 400);
        }else{
            $datos = (array)$request['feriado'];
            //return $datos;
            DB::table("calendarios")->insert($datos);
        }


        $sql = DB::table("calendarios")
            ->where('calendarios.fecha','>=',date('d-m-Y'))
            ->where('atencion', '=', 'feriado')
            ->get();
        return $sql;

        //return $resp;
    }
    public static function update_feriados($request)
    {

        return $request;
        if(DB::table("calendarios")->where('fecha', '=', $request['feriado']['fecha'])->exists()){
            return new Response(['message' => 'Datos dupliacados' ], 400);
        }else{
            $datos = (array)$request['feriado'];
            //return $datos;
            DB::table("calendarios")->insert($datos);
        }


        $sql = DB::table("calendarios")
            ->where('calendarios.fecha','>=',date('d-m-Y'))
            ->where('atencion', '=', 'feriado')
            ->get();
        return $sql;

        //return $resp;
    }
    public static function delete_feriados($request)
    {
        if(DB::table("calendarios")->where('fecha', '=', $request['fecha']['fecha'])->exists()){
            DB::table("calendarios")->where('fecha', '=', $request['fecha']['fecha'])
            ->delete();
            
            
        }else{
            return new Response(['message' => 'no se lo tiene registrado' ], 400);
            /*
            $datos = (array)$request['fecha'];
            //return $datos;
            DB::table("calendarios")->insert($datos);*/
        }
       
        return $request;
    }
}
