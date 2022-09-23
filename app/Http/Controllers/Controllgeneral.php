<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Controllgeneral extends Controller
{
    //

    public function index()
    {
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
        return $request['datos'];
        $datos = $request['datos'];
        DB::table('configenerals')->insert($datos);

        return $request['datos'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Controllgeneraluracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(Controllgeneral $configuracion)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Controllgeneraluracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Controllgeneral $configuracion)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Controllgeneraluracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Controllgeneral $configuracion)
    {
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Controllgeneraluracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Controllgeneral $configuracion)
    {
        //
    }
}
