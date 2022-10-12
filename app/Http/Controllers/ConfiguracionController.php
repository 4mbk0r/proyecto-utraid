<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Exception;
use Exception as GlobalException;
use Illuminate\Database\QueryException;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        date_default_timezone_set("America/La_Paz");
        $date = date_create();
        $list_config = DB::table('configuracions')
            ->select('*')
            ->where('fecha_final', '>', date_format($date, "Y-m-d"))
            ->get();
        
        $date = date_format($date, "Y-m-d H:i:s");
        return inertia('Configuracions', [
            'configuracion' => $list_config,
            'fecha_server' => $date,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        
        date_default_timezone_set("America/La_Paz");
        $date = date_create();
        $edit = $request['datos'];
        $n_fecha_final = date($edit['fecha_inicio']);
        $n_fecha_final = date("Y-m-d", strtotime($n_fecha_final . "- 1 days"));
        if($edit['tipo']=='permanente'){
            try {
                $default_actual =  DB::table('configuracions')
                    ->where('principal', true)
                    ->update(['principal' => false, 'fecha_final' => $n_fecha_final]);
    
                $edit['historial'] = $edit['id'];
                unset($edit['id']);
                $default_actual =  DB::table('configuracions')->insertGetId($edit);
                $salas = $request['salas'];
                $p = [];
                foreach ($salas as $id => $row) {
                    # code...
                    //$row->id;
                    $row['id']=$default_actual;
                    unset($row['sala']);
                    DB::table('salas')->insert($row);
                }
               
                
            } catch (\Throwable $th) {
                return $th;
            }
        }
        if($edit['tipo']=='temporal'){
            try {
                unset($edit['id']);
                $default_actual =  DB::table('configuracions')->insertGetId($edit);
                $temp = $request['fecha_temporales'];
                foreach ($temp as $id => $row) {
                    $p = [
                        'id'=>$default_actual,
                        'fecha'=>$row
                    ];
                    DB::table('cita_tiene_configuracions')->insert($p);    
                }
                $salas= $request['salas'];
                foreach ($salas as $id => $row) {
                    $row['id']=$default_actual;
                    unset($row['sala']);
                    DB::table('salas')->insert($row);    
                }
            } catch (\Throwable $th) {
                return $th;
            }
        }
        $list_config = DB::table('configuracions')
                    ->select('*')
                    ->where('fecha_final', '>', date_format($date, "Y-m-d"))
                    ->get();
        return $list_config;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(int $configuracion)
    {
        //
        $dato = DB::table('salas')->where('id', $configuracion)->get();
        $n_salas =  count($dato);
        $datos = [
            'salas' => $dato,
            'n_sala' => $n_salas
        ];
        return $datos;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracion $configuracion)
    {
        //


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        //

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $configuracion)
    {
        //
        date_default_timezone_set("America/La_Paz");
        $date = date_create();
        try {
            $list_item = DB::table('configuracions')->where('id', '=', $configuracion)->get();
            if (count($list_item) == 1) {
                $item = $list_item[0];
                DB::table('configuracions')->where('historial', '=', $item->id)
                    ->update(['historial' => $item->historial]);
                DB::table('configuracions')->where('id', '=', $item->historial)
                    ->update(['fecha_final' => $item->fecha_final, 'principal' => $item->principal]);
                DB::table('configuracions')->delete($item->id);
                DB::table('salas')->where('id', '=', $item->id)->delete();
            }
        } catch (\Throwable $th) {
            return $th;
        }
        $list_config = DB::table('configuracions')
            ->select('*')
            ->where('fecha_final', '>', date_format($date, "Y-m-d"))
            ->get();
        return $list_config;
    }
}
