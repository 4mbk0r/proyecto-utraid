<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use stdClass;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExcelPaciente extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public static function get_sheet(Request $request)
    {
        //return $request;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $objPHPExcel = IOFactory::load($file);
            $hojas = $objPHPExcel->getSheetCount();
            $resp = [];
            for ($i = 0; $i < $hojas; $i++) {
                $nombre_hoja = $objPHPExcel->getSheet($i)->getTitle();
                array_push($resp, ['id' => $i, 'nombre_sheet' => $nombre_hoja]);
            }
            return $resp;
        } else {
            return response()->json(['message' => 'No se encontró ningún archivo'], 400);
        }
    }
    public static function validate_date(Request $request)
    {

        $table = 'personas';
        $hearder =
            DB::table("information_schema.columns")
            ->select("column_name as nombre", "data_type as tipo", "is_nullable", DB::raw('false as sw'))
            ->where("table_name", "=", $table)
            ->get();

        $registro =
            DB::table("information_schema.columns")
            ->select("column_name as nombre", "data_type as tipo", "is_nullable", DB::raw('false as sw'))
            ->where("table_name", "=", "registros")
            ->get();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $objPHPExcel = IOFactory::load($file);

            //$hojas = $objPHPExcel->getSheetCount();
            //$resp = [];
            $sheet_id = json_decode($request->input('sheet'))->id;
            $sheet = $objPHPExcel->getSheet($sheet_id);
            $i = 0;
            $hearder_sheet = [];
            $r = [];
            $nro_blanco = 0;
            $nro_repetidos = 0;
            $nro_guardados = 0;
            foreach ($sheet->getRowIterator() as $row) {


                $f = new stdClass();
                $j = 0;
                foreach ($row->getCellIterator() as $cell) {

                    if ($i == 0) {
                        $valor_buscar = $cell->getValue();
                        array_push($hearder_sheet,  trim($cell->getValue()));
                        if ($valor_buscar == '') {
                            return  throw new HttpException(500, 'Los encabezados del excel son blancos. Este es error.');
                        }
                    } else {
                        $key = $hearder_sheet[$j];

                        $f->$key =  trim($cell->getValue());
                        $value =  trim($cell->getValue());
                    }
                    $j++;
                    // Do something with the value
                }

                if ($i > 0) {
                    $rules = [
                        'nombres' => 'required',
                        'ci' => 'required'
                    ];
                    //$resp = new Request((array)$f);
                    $validator = Validator::make(json_decode(json_encode($f), true), $rules);
                    if ($validator->passes()) {
                        // La validación fue exitosa
                        $query = db::table('personas')
                            ->where('ci', '=', $f->ci)
                            ->where('expedido', '=', $f->expedido)
                            ->get();
                        //array_push($r, $query);

                        if (count($query) == 0) {
                            //array_push($r, $hearder);
                            $insertar = new stdClass();
                            foreach ($hearder as $key => $ui) {
                                # code...
                                $vk = ($ui->nombre);

                                if (property_exists($f, $vk)) {
                                    $insertar->$vk = $f->$vk;
                                } else {
                                    $insertar->$vk = null;
                                }
                            }
                            $dato_registro = new stdClass();
                            foreach ($registro as $key => $ui) {
                                # code...
                                $vk = ($ui->nombre);

                                if (property_exists($f, $vk)) {
                                    $dato_registro->$vk = $f->$vk;
                                } else {
                                    $dato_registro->$vk = null;
                                }
                            }
                            $u = (array)$insertar;
                            unset($u['id']);
                            unset($u['register']);
                            $codigo = !empty($u['ap_paterno']) ?  $u['ap_paterno'][0] : '';
                            $codigo .= !empty($u['ap_materno']) ?  $u['ap_materno'][0] : '';
                            $codigo .= !empty($u['nombres']) ?  $u['nombres'][0] : '';
                            if (strlen($codigo) == 2) {
                                $codigo .= '_';
                            }
                            $u['id'] = DB::raw("generate_auto_increment('" . $codigo . "')");

                            $query = db::table('personas')
                                ->insertGetId($u);
                            if (!empty($query)) {
                                $dato_registro = (array) $dato_registro;
                                $dato_registro['id_persona'] = $query;
                                unset($dato_registro['id']);
                                $query = db::table('registros')
                                    ->insert($dato_registro);
                            }

                            $nro_guardados++;
                            //array_push($r, $insertar);
                        } else {
                            if (count($query) == 1) {
                                $paciente = $query[0];
                                $dato_registro = new stdClass();
                                foreach ($registro as $key => $ui) {
                                    # code...
                                    $vk = ($ui->nombre);

                                    if (property_exists($f, $vk)) {
                                        $dato_registro->$vk = $f->$vk;
                                    } else {
                                        $dato_registro->$vk = null;
                                    }
                                }
                                $resp = db::table('registros')
                                    ->where('id_persona', '=', $paciente->id)
                                    ->where('fecha_registro', '=', $dato_registro->fecha_registro)
                                    ->exists();
                                if (!$resp) {

                                    $dato_registro = (array) $dato_registro;
                                    $dato_registro['id_persona'] = $paciente->id;
                                    unset($dato_registro['id']);
                                    $query = db::table('registros')
                                        ->insert($dato_registro);
                                        $nro_repetidos++;
                                }
                            }
                            //array_push($r, $f);
                            
                        }
                    } else {

                        $nro_blanco++;
                        //array_push($r, $f);
                        // La validación falló, manejar el error
                    }
                }
                $i++;
            }
            unset($request);
            return response()->json([
                'file' => $hearder_sheet, 'header' => $hearder,
                'nro_repetido' => $nro_repetidos, 'nro_blancos' => $nro_blanco,
                'nro_insertado' => $nro_guardados,

                'success' => true
            ]);
            return $file;
        } else {
            return response()->json(['message' => 'No se encontró ningún archivo'], 400);
        }
    }


    public static function downloadExcel(Request $request)
    {


        $file = $request->file('file');
        $sheet_id = json_decode($request->input('sheet'))->id;
        $spreadsheet = IOFactory::load($file);
        //$spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getSheet($sheet_id);

        // Validar los datos antes de agregarlos al archivo de Excel
        $data = $sheet->toArray();


        // Validar los datos antes de agregarlos al archivo de Excel
        $validator = Validator::make($data, [
            '*.nombre' => 'required',
            '*.ci' => 'required',
            //'*.2' => 'email',
        ], [
            '*.nombre.required' => 'El campo nombre es obligatorio',
            '*.ci.required' => 'El campo apellido es obligatorio',
            //'*.2.email' => 'El correo electrónico no es válido',
        ]);

        if ($validator->fails()) {
            //return $validator->failed();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray($data, null, 'A1');


            // Pintar las celdas con errores de validación
            $failedRules = $validator->failed();

            /*$r = [];
            foreach ($failedRules as $row => $rules) {
                $fila =  explode(".", $row);
                array_push($r,$fila);
            }
            return $r;*/
            foreach ($failedRules as $row => $rules) {
                foreach ($rules as $column => $rule) {
                    $fila =  explode(".", $row);
                    $cell = $sheet->getCellByColumnAndRow(intval($fila[1]) + 1, intval($fila[0]) + 1);
                    // Definir el estilo de la celda
                    $style = [
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => ['rgb' => 'FF0000']
                        ],
                        'font' => [
                            'color' => ['rgb' => 'FFFFFF']
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000']
                            ]
                        ]
                    ];
                    $cell->getStyle()->applyFromArray($style);
                }
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'archivo-de-ejemplo-con-errores.xlsx';
            $writer->save($filename);

            return response()->download($filename)->deleteFileAfterSend(true);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, null, 'A1');

        $writer = new Xlsx($spreadsheet);
        $filename = 'archivo-de-ejemplo.xlsx';
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    private static function validateData($data)
    {
        $rules = [
            '*.0' => 'required',
            '*.1' => 'required',
            '*.2' => 'email',
        ];

        $messages = [
            '*.0.required' => 'El campo nombre es obligatorio',
            '*.1.required' => 'El campo apellido es obligatorio',
            '*.2.email' => 'El correo electrónico no es válido',
        ];

        $validator = validator()->make($data, $rules, $messages);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
