<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\establecimiento;
use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
            ->where('cargo', '!=', 'ADMIN')
            
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
        //return $request;
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
    public function destroy(String $personal)
    {
        //
        return 
        DB::table('users')
    ->where('username', '=', $personal)
    ->update(['estado' => 'inactivo']);
        //return true;

    }
    public static function activar_personal(Request $request)
    {
        //return $request;
        return 
        DB::table('users')
    ->where('username', '=', $request->username)
    ->update(['estado' => 'activo']);
        
        //
        
        //return true;

    }
    
    public static function subir_personal(Request $request)
    {

        $personal  = $request['datos'];
        $fallos = [];
        //return  $personal;//gettype($personal);
        foreach ($personal as $key => $i) {

            //return $input;
            //return $input;
            $input =  (array) $i;

            # code...
            try {
                //$select = DB::table('users')->where('ci','==', $input['ci'] )->get();
                if (!User::where('ci', $input['ci'])->exists()) {
                    $reps = User::create([
                        'nombre' => $input['nombres'],
                        'ap_paterno' => $input['ap_paterno'],
                        'ap_materno' => $input['ap_materno'],
                        'ci' => $input['ci'],
                        'cargo' => $input['cargo'],
                        'expedido' => trim($input['expedido']),
                        'username' => trim($input['ci']),
                        'password' => Hash::make($input['ci']),
                    ]);
                    if ($reps) {
                        if (establecimiento::where('nombre', $input['establecimiento'])->exists()) {
                            $dato = [
                                'id_usuario' => $input['ci'],

                                'id_establecimiento' => $input['establecimiento'],
                            ];
                            DB::table('contratos')->insert($dato);
                        } else {
                            array_push($fallos, $input);
                            User::where('ci', $input['ci'])->delete();
                        }
                    }
                }
            } catch (QueryExecuted $e) {
                //return $e; 

                //User::where('ci', $input['ci'])->delete();
                $i['error'] = $e;
            }
        }

        return $fallos;
        /**/
    }
    public static function personal_servicio()
    {
        //return $request;
        $cargo = DB::table('users')
            ->leftJoin('cargos', 'cargos.cargo', '=', 'users.cargo')
            ->where('cargos.servicio', '=', 'true')
            //->where('cargo', '=','recepcionista')
            ->get();
        return $cargo;
    }
    public static function plantilla()
    {
        $data = DB::table('users')
            ->select(
                'users.nombres',
                'users.ap_paterno',
                'users.ap_materno',
                'users.ci',
                'users.cargo',
                'users.expedido',
                'contratos.id_establecimiento as establecimiento'
                // Agrega más columnas según tus necesidades
            )
            ->leftJoin('contratos', 'contratos.id_usuario', '=', 'users.username')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Agrega encabezados a la primera fila
        $encabezados = ['nombres', 'ap_paterno', 'ap_materno', 'ci', 'cargo', 'expedido', 'establecimiento'];
        foreach ($encabezados as $key => $encabezado) {
            $columna = chr(65 + $key); // Convierte números a letras para las columnas (A, B, C, ...)
            $sheet->setCellValue($columna . '1', ucfirst($encabezado)); // ucfirst para la primera letra en mayúscula
        }

        // Itera sobre los datos y agrega filas al archivo Excel
        $row = 2; // Comenzamos desde la fila 2 después de los encabezados
        foreach ($data as $user) {
            foreach ($encabezados as $key => $campo) {
                // Utiliza los nombres de columna de la base de datos directamente
                $valor = $user->$campo;
                $columna = chr(65 + $key); // Convierte números a letras para las columnas (A, B, C, ...)
                $sheet->setCellValue($columna . $row, $valor);
            }
            $row++;
        }

        // Crea el archivo Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'usuarios.xlsx';

        // Configura las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Envía el archivo al navegador
        $writer->save('php://output');
    }

    private static function generateExcelResponse($data, $fileName)
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            foreach ($data as $row) {
                // Aquí puedes personalizar cómo se escriben los datos en el archivo Excel
                fputcsv($handle, [
                    $row->nombres,
                    $row->ap_paterno,
                    $row->ap_materno,
                    // Agrega más campos según las columnas seleccionadas arriba
                ]);
            }
            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
