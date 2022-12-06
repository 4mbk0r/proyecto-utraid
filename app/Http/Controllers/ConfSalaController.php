<?php

namespace App\Http\Controllers;

use App\Models\conf_sala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfSalaController extends Controller
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
     * @param  \App\Models\conf_sala  $conf_sala
     * @return \Illuminate\Http\Response
     */
    public function show(int $conf_sala)
    {
        //
        $sala = DB::table('conf_salas')
        ->leftJoin('salas', 'salas.id', '=', 'conf_salas.id_sala' )
        //->leftJoin('config_horarios', 'config_horarios.id_conf_sala', '=', 'conf_salas.id')
        //->leftJoin('horarios', 'horarios.id_horario', '=', 'config_horarios.id_horario')
        ->where('id_calendario', '=', $conf_sala)
        ->get();
        return $sala;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\conf_sala  $conf_sala
     * @return \Illuminate\Http\Response
     */
    public function edit(conf_sala $conf_sala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\conf_sala  $conf_sala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, conf_sala $conf_sala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\conf_sala  $conf_sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(conf_sala $conf_sala)
    {
        //
    }
}
