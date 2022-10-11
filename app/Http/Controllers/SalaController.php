<?php

namespace App\Http\Controllers;

use App\Models\sala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return inertia('Micomponet/Sala');
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
        return $request['datos'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function show(Request $sala)
    {
        //
        return $sala;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function edit(sala $sala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $sala)
    {
        //
        return $sala;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id, int $sala)
    {
        //
        return $id;
    }
    public static function eliminar(Request $item)
    {
        //
        $item = $item['dato'];
        try {
            DB::table('salas')
                ->where('id', '=', $item['id'])
                ->where('sala', '=', $item['sala'])
                ->delete();
        } catch (\Throwable $th) {
            return $th;
        }
        $datos = DB::table('salas')
            ->where('id', '=', $item['id'])->get();
        return $datos;
    }
}
