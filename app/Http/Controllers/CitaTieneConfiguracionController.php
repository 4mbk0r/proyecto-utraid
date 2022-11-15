<?php

namespace App\Http\Controllers;

use App\Models\cita_tiene_configuracion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class CitaTieneConfiguracionController extends Controller
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
     * @param  \App\Models\cita_tiene_configuracion  $cita_tiene_configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(string $fecha)
    {
        //
        $list_config = DB::table('cita_tiene_configuracions')
            ->select('*')
            ->where('fecha', '=', $fecha)
            ->get();
        if (sizeof($list_config) == 0) {
            $list_config = DB::table('configuracions')
                ->select('*')
                ->where('fecha_inicio', '<=', $fecha)
                ->where('fecha_final', '>=', $fecha)
                ->where('tipo', '>=', 'permanente')
                ->get();
        }
        $salas = [];
        if (sizeof($list_config) > 0) {
            try {
                $salas = DB::table('salas')
                    ->select('*')
                    ->where('id', '=', $list_config[0]->id)
                    ->get();
                $salas_disponibles = DB::table('salas')
                    //->select('salas.sala, salas.descripcion')
                    ->join('horarios', "horarios.sala",'=', "salas.sala")
                    ->leftJoin("agendas", function ($join) use ($fecha) {
                        $join->on("agendas.horario", "=", "horarios.id_horario");
                        $join->where('agendas.fecha','=',$fecha);
                      })
                    ->whereNull('agendas.horario')
                    ->groupBy('salas.sala', 'salas.descripcion')
                    ->select('salas.sala', 'salas.descripcion')
                    ->orderBy('salas.descripcion')
                    ->get();
                $resp = [
                    'salas'=> $salas,
                    'salas_diponibles'=>$salas_disponibles
                ];
            } catch (\Throwable $th) {
                return $th;
            }
            return $resp;
        }
        $resp = [
            'salas'=> $salas,
            'salas_diponibles'=>$salas
        ];
        return $salas;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cita_tiene_configuracion  $cita_tiene_configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(cita_tiene_configuracion $cita_tiene_configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cita_tiene_configuracion  $cita_tiene_configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cita_tiene_configuracion $cita_tiene_configuracion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cita_tiene_configuracion  $cita_tiene_configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(cita_tiene_configuracion $cita_tiene_configuracion)
    {
        //
    }
    public static function verificar_fecha(String $fecha)
    {
        $list_config = DB::table('cita_tiene_configuracions')
            ->select('*')
            ->where('fecha', '>=', date($fecha))
            ->get();
        $verificar = false;
        if (count($list_config) == 0) {
            $verificar = true;
        }
        $resp = [
            'lista_fechas' => $list_config,
            'verificar' => $verificar,
            'n' => count($list_config)
        ];
        return $resp;
    }
    public static function verificar_rangofecha(Request $request)
    {
        $temp = [];
        $fecha = $request['fecha'];
        $conf_default = [];
        foreach ($fecha as $key => $value) {
            try {
                $list_config = DB::table('cita_tiene_configuracions')
                    ->select('*')
                    ->where('fecha', '=', date($value))
                    ->get();
                $fecha_entre = DB::table('configuracions')
                    ->select('*')
                    ->where('fecha_inicio', '<=', date($value))
                    ->where('fecha_final', '>=', date($value))
                    ->where('tipo', '=', 'permanente')->get();
            } catch (\Throwable $th) {
                return $th;
            }
            if (count($list_config) > 0) {


                array_push($temp, $value);
            } else {
                array_push($conf_default, $fecha_entre[0]);
            }
        }
        $verificar = false;
        if (count($temp) == 0) {
            $verificar = true;
        }

        $resp = [
            'lista_fechas' => $temp,
            'verificar' => $verificar,
            'default' => $conf_default,
            'n' => count($temp)
        ];
        return $resp;
    }
}
