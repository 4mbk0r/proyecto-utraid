<?php

namespace App\Http\Controllers;

use App\Models\agenda;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        unset($cita['hora_inicio']);
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
            return response()->json(['error' => 'You need to add a card first'], 409);
        }

        return $request['cita'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(string $agenda)
    {
        //

        $date = date_create(date($agenda), timezone_open('America/La_Paz'));
        $date = date_format($date, 'Y-m-d');
        /*if (Cache::has('citas' . $date)) {
            $cita_fecha =  Cache::get('citas' . $date);
        } else {*/


        $agenda = DB::table('agendas')
            ->leftJoin('persona_citas', 'agendas.ci_paciente', '=', 'persona_citas.ci')
            ->leftJoin('horarios', 'agendas.horario', '=', 'horarios.id_horario')
            ->where('fecha', $date)
            ->get();
        // Cache::put('citas' . $date, $cita_fecha);
        //}
        return $agenda;
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

                /*
                    select horarios.sala, salas.descripcion from salas
JOIN horarios on horarios.sala = salas.sala 
LEFT join agendas on agendas.horario = horarios.id_horario and agendas.fecha = '2022-11-30'
--RIGHT join salas on horarios.sala = salas.sala 
where agendas.horario is NULL  --and agendas.consultorio =  horarios.sala
group by horarios.sala, salas.descripcion
                */
                $salas = DB::table('salas')
                    //->select('salas.sala, salas.descripcion')
                    ->join('horarios', "horarios.sala",'=', "salas.sala")
                    ->leftJoin("agendas", function ($join) use ($agenda) {
                        $v = $agenda['fecha'];
                        $join->on("agendas.horario", "=", "horarios.id_horario");
                        $join->where('agendas.fecha','=',$v);
                      })
                    ->whereNull('agendas.horario')
                    
                    ->groupBy('salas.sala', 'salas.descripcion')
                    ->select('salas.sala', 'salas.descripcion')
                    ->get();
            } catch (\Throwable $th) {
                return $th;
            }
            return $salas;
        }


        return $configuracion;
    }
}
