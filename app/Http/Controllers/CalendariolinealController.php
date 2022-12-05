<?php

namespace App\Http\Controllers;

use App\Models\Calendariolineal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendariolinealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static $table = 'calendariolineals';
    public function index()
    {
        //
        $tabla = 'calendariolineals';
        $linea_calendarios = DB::table($tabla)
            ->select('calendariolineals.id as id_calendario', '*')
            ->leftJoin('configuracions', 'configuracions.id', '=', $tabla . '.id_configuracion')
            //->leftJoin('configuracions', 'configuracions.id','=',$tabla.'.id_configuracion')
            ->orderBy('fecha_inicio', 'asc')
            ->get();
        return $linea_calendarios;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $tabla = 'calendariolineals';
        $linea_calendarios = DB::table($tabla)
            ->select('calendariolineals.id as id_calendario', '*')
            ->leftJoin('configuracions', 'configuracions.id', '=', $tabla . '.id_configuracion')
            //->leftJoin('configuracions', 'configuracions.id','=',$tabla.'.id_configuracion')
            ->orderBy('fecha_inicio', 'asc')
            ->get();
        return $linea_calendarios;
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
        $antigua = $request['configuracion_antigua'];
        $nueva = $request['calendario'];
        try {
            $conf_antigua = DB::table(self::$table)
                ->where('id', '=', $antigua['id_calendario'])
                ->get()[0];

            //return $conf_antigua;
            //$conf_antigua->fecha_final = date('Y-m-d', strtotime($nueva['fecha_inicio'] . ' - 1 days'));
            //unset($conf_antigua->id);
            $resul = DB::table(self::$table)
                ->where('id', '=', $antigua['id_calendario'])
                ->update(['fecha_final' => date('Y-m-d', strtotime($nueva['fecha_inicio'] . ' - 1 days')), 'principal' => false]);

            //DB::table(self::$table)->update($antigua);
        } catch (\Throwable $th) {
            return $th;
        }
        try {
            $conf_nueva = clone $conf_antigua;
            $conf_nueva->fecha_inicio = $nueva['fecha_inicio'];
            $conf_nueva->fecha_final = $conf_antigua->fecha_final;
            $conf_nueva->historial = $conf_antigua->id;
            unset($conf_nueva->id);
            DB::table(self::$table)->insert((array)$conf_nueva);
        } catch (\Throwable $th) {
            $resul = DB::table(self::$table)
                ->where('id', '=',  $antigua['id_calendario'])
                ->update(['fecha_final' => $conf_antigua->fecha_final]);
            return $th;
        }


        return $conf_nueva;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calendariolineal  $calendariolineal
     * @return \Illuminate\Http\Response
     */
    public function show(Calendariolineal $calendariolineal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Calendariolineal  $calendariolineal
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendariolineal $calendariolineal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendariolineal  $calendariolineal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendariolineal $calendariolineal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendariolineal  $calendariolineal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendariolineal $calendariolineal)
    {
        //
        //return $calendariolineal;
        if ($calendariolineal->id > 1) {
            try {
                $tabla = 'calendariolineals';
                $conf_antigua = DB::table($tabla)->where('id', '=', $calendariolineal->id);
                $resp = $conf_antigua->delete();
                if ($resp) {
                    //DB::table(self::$table)->delete($calendariolineal->id_calendario);
                    //$conf_antigua->fecha_final = date('Y-m-d', strtotime($nueva['fecha_inicio'] . ' - 1 days'));
                    //unset($conf_antigua->id);
                    //$conf_antigua =  $conf_antigua[0];
                    $resul = DB::table(self::$table)
                        ->where('id', '=', $calendariolineal->historial)
                        ->update(['fecha_final' => $calendariolineal->fecha_final, 'principal' => $calendariolineal->principal]);

                    //DB::table(self::$table)->update($antigua);

                }
            } catch (\Throwable $th) {
                DB::table($tabla)->insert((array)$calendariolineal);
                return $th;
            }
        }

        $linea_calendarios = DB::table($tabla)
            ->select('calendariolineals.id as id_calendario', '*')
            ->leftJoin('configuracions', 'configuracions.id', '=', $tabla . '.id_configuracion')
            //->leftJoin('configuracions', 'configuracions.id','=',$tabla.'.id_configuracion')
            ->orderBy('fecha_inicio', 'asc')
            ->get();
        return $linea_calendarios;
    }
}
