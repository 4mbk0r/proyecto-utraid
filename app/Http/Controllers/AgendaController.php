<?php

namespace App\Http\Controllers;

use App\Models\agenda;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
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
        $cita = $request['cita'];

        try {
            $query = DB::table('cita_tiene_configuracions')->where('fecha', '=', $cita['fecha'])->get();
            if (sizeof($query) == 0) {
                $query = DB::table('configuracions')->where('fecha_inicio', '<=', $cita['fecha'])
                    ->where('fecha_final', '>=', $cita['fecha'])
                    ->where('tipo', '=', 'permanente')
                    ->get();
                $use = [
                    'fecha' => $cita['fecha'],
                    'id' => $query[0]->id
                ];
                $query = DB::table('cita_tiene_configuracions')->insert($use);
            }
            DB::table('agendas')->insert($cita);
        } catch (\Throwable $th) {
            return $th;
        }

        return $request['cita'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(agenda $agenda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function edit(agenda $agenda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, agenda $agenda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(agenda $agenda)
    {
        //
    }
    public static function buscar_consultorios(Request $request)
    {
        $agenda = $request['datos_agenda'];
        try {
            $configuracion = DB::table('cita_tiene_configuracions')
                ->select('*')
                ->where('fecha', '=', $agenda['fecha'])
                ->get();
        } catch (\Throwable $th) {
            return $th;
        }
        if (sizeof($configuracion) <= 0) {

            try {
                $configuracion = DB::table('configuracions')
                    ->select('*')
                    ->where('fecha_inicio', '<=', $agenda['fecha'])
                    ->where('fecha_final', '>=', $agenda['fecha'])
                    ->where('tipo', '=', 'permanente')
                    ->get();
            } catch (\Throwable $th) {
                return $th;
            }
        }
        if (sizeof($configuracion) == 1) {
            $conf = $configuracion[0];
            try {
                $salas = DB::table('salas')
                    ->select('*')
                    ->where('id', '=', $conf->id)
                    ->get();
            } catch (\Throwable $th) {
                return $th;
            }
            return $salas;
        }


        return $configuracion;
    }
}
