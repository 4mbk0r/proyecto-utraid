<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\VarDumper\Cloner\Data;

use function PHPSTORM_META\type;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lista = DB::table('users')
            ->where('cargo', '!=', 'Admin')
            ->get();
        return $lista;
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
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function show(Personal $personal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function edit(Personal $personal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $personal)
    {
        $usuario = json_decode(json_encode($request['usuario']), true);
        try {
            $lista = DB::table('users')
                ->where('ci', '=', $personal)
                ->update($usuario);
            //
        } catch (\Throwable $th) {
            return $th;
            //new Response(['message' => 'th'], 400);
        }

        return $usuario;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Personal $personal)
    {
        //
    }
    public static function subir_personal(Request $request)
    {

        $personal  = json_decode($request['datos']);
        //return  gettype($personal);
        foreach ($personal as $key => $input) {
            
            $input = (array) $input;
            return $input['ap_paterno'];
            # code...
            try {
                $reps = User::create([
                    'nombre' => $input['nombres'],
                    'ap_paterno' => $input['ap_paterno'],
                    'ap_materno' => $input['ap_materno'],
                    'ci' => $input['ci'],
                    'cargo' => $input['cargo'],
                    'expedido' => $input['expedido'],
                    'email' => (!isset($input['email'])) ?  null : $input['email'],
                    'celular' => (!isset($input['celular'])) ?  null : $input['celular'],
                    'username' => $input['ci'],
                    'password' => Hash::make($input['password']),
                ]);
                if ($reps) {
                    $dato = [
                        'id_usuario' => $input['ci'],
                        'id_establecimiento' => $input['establecimiento'],
                    ];
                    DB::table('contratos')->insert($dato);
                }
            } catch (QueryExecuted $e) {
                return $e;
                
            }
        }
        /**/
    }
}
