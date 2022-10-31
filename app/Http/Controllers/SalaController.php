<?php

namespace App\Http\Controllers;

use App\Models\sala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        try {
            $id_last = DB::table('salas')->insertGetId($request['datos']);
            $horario = $request['horario'];
            foreach ($horario as $id => $row) {
                $row['id'] = $id_last;
                //unset($row['sala']);
                DB::table('horarios')->insert($row);
            }
        } catch (\Throwable $th) {
            return $th;
            //new Response(['message' => 'th'], 400);
        }
        $salas = DB::table('salas')->get();
        return $salas;
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
        $consultorio = $request['datos'];
        $horario = $request['horario'];
        //return $horario;
        try {
           
            DB::table('salas')->where('sala',$consultorio['sala'])
                ->where('id', $consultorio['id'])->update($consultorio);

            DB::table('horarios')
                ->where('sala', '=', $consultorio['sala'])
                ->delete();
            
            
            foreach ($horario as $id => $row) {
                //$row['id'] = $id_last;
                //unset($row['sala']);
                
                DB::table('horarios')
                    ->insert($horario[$id]);
            }
        } catch (\Throwable $th) {
            return $th;
        }
        $salas = DB::table('salas')->get();
        return $salas;
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
