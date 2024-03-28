<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Common\Creator\ReaderFactory;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use stdClass;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Validator;
use PHPExcel_Shared_Date;
use Illuminate\Support\Facades\Storage;
use PHPExcel_IOFactory;
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
        $request->validate([
            'file' => 'required|mimes:xlsx,xlsm,xltx,xltm,xls,csv',
        ]);

        $file = $request->file('file');
        $temporaryPath = $file->store('uploads');
        $originalName = $file->getClientOriginalName();
        $reader = ReaderEntityFactory::createReaderFromFile($originalName); // BoxSpout 3.3

        $reader->open($file);
        // Get the sheets from the file
        $sheets = $reader->getSheetIterator();
        $sheet_array = [];
        // Process each sheet
        $i = 0;
        $dato_final = [];
        foreach ($sheets as $sheet) {
            // Get the sheet name
            $sheetName = $sheet->getName();
            array_push($sheet_array, ['id' => $i, 'nombre_sheet' => $sheetName]);
            $i++;
            $rows = $sheet->getRowIterator();
            $datos = [];
            // Process each row
            $j = 0;
            foreach ($rows as $row) {
                // Get the cells from the row
                $cells = $row->getCells();
                $valores = [];

                // Process each cell
                foreach ($cells as $cell) {
                    // Get the cell value
                    $value = $cell->getValue();
                    $value = self::convertExcelDate($value);
                    array_push($valores, $value);
                    // Process the value as needed
                    // ...
                }
                if ($j == 10) {
                    break;
                }
                $j++;
                array_push($datos, $valores);
            }
            array_push($dato_final, $datos);
        }

        // Close the reader
        $reader->close();

        // Return a response
        return response()->json([
            'success' => true,
            'message' => 'Las hojas del archivo Excel se han procesado correctamente.',
            'datos' => $sheet_array,
            'valores' => $dato_final
        ]);
    }
    public static function convertExcelDate($value)
    {
        // Excel/LibreOffice Calc fecha base del 1 de enero de 1900
        // Verificar si el valor es numérico y podría ser una fecha serial de Excel
        if (is_object($value)) {
            //return $value->format('Y');
            if (get_class($value) === 'DateTime') {
                return $value->format('d-m-Y');;
            }
        }

        return $value;
    }
    /*
    return $file;

    $disk = Storage::disk('local');
    /*if ($request->hasFile('file')) {
        try {

            $file = $request->file('file');
            //$nombreArchivo = $file->getClientOriginalName();
            $extensionArchivo = $file->getClientOriginalExtension();
            //$nombreArchivoUnico = uniqid() . '_' . $nombreArchivo;
            //$nombreArchivoUnico = uniqid() . '_' . $file->getClientOriginalName();

            // Guardar el archivo en el almacenamiento local de Laravel (Storage)
            Storage::put('open' . $extensionArchivo, file_get_contents($file));

            //Cache::put($nombreArchivoUnico . '.' . $extensionArchivo, file_get_contents($file));

            $objPHPExcel = IOFactory::load($file);
            //$disk->put('openexcel.xlsx', $objPHPExcel->save('php://output'));
            //$disk->put('nombre_de_archivo.xlsx', fwrite($objPHPExcel->getActiveSheet()->getStream(), ""));
            //  Cache::put('open', $file);

            $hojas = $objPHPExcel->getSheetCount();
            $resp = [];
            for ($i = 0; $i < $hojas; $i++) {
                $nombre_hoja = $objPHPExcel->getSheet($i)->getTitle();
                array_push($resp, ['id' => $i, 'nombre_sheet' => $nombre_hoja]);
            }
            return $resp;
            //code...
        } catch (Exception $th) {
            //throw $th;
            return response()->json(['message' => $th], 400);
        }
    } else {
        return response()->json(['message' => 'No se encontró ningún archivo'], 400);
    }*/
    public static function validate_date(Request $request)
    {

        $id_sheet = json_decode($request['sheet'], true)['id'];
        //return $id_sheet;
        $table = 'personas';
        $hearder =
            DB::table("information_schema.columns")
            ->select("column_name as nombre")
            ->where("table_name", "=", $table)
            ->pluck('nombre')->toArray();


        $hregistro =
            DB::table("information_schema.columns")
            ->select("column_name as nombre")
            ->where("table_name", "=", "evaluacions")
            ->pluck('nombre')->toArray();
        if ($request->hasFile('file')) {

            $request->validate([
                'file' => 'required|mimes:xlsx,xlsm,xltx,xltm,xls,csv',
            ]);

            $file = $request->file('file');
            //$temporaryPath = $file->store('uploads');
            $originalName = $file->getClientOriginalName();
            $reader = ReaderEntityFactory::createReaderFromFile($originalName); // BoxSpout 3.3

            $reader->open($file);
            // Get the sheets from the file
            $sheets = $reader->getSheetIterator();
            $sheet_array = [];
            // Process each sheet
            $i = 0;
            $dato_final = [];
            foreach ($sheets as $sheet) {
                // Get the sheet name
                if ($i === $id_sheet) {
                    //$sheetName = $sheet->getName();
                    //array_push($sheet_array, ['id' => $i, 'nombre_sheet' => $sheetName]);

                    $rows = $sheet->getRowIterator();
                    $datos = [];
                    // Process each row
                    $j = 0;
                    $hearder_sheet = [];
                    foreach ($rows as $row) {
                        // Get the cells from the row
                        $cells = $row->getCells();
                        $valores = [];
                        // Process each cell
                        $fila = [];
                        $h = 0;
                        foreach ($cells as $cell) {
                            // Get the cell value
                            $value = $cell->getValue();
                            $value = self::convertExcelDate($value);
                            if ($j == 0) {
                                //$valor_buscar = $cell->getValue();
                                array_push($hearder_sheet,  trim($value));
                            } else {
                                $key = $hearder_sheet[$h];
                                $fila[$key] = trim($value);
                          }
                            $h++;
                        }
                        /*if ($j == 10) {
                            break;
                        }*/
                        if ($j > 0) {
                            //array_push($datos, $fila);
                            /* $fila = array_map(function ($fila) use ($hearder) {
                                return ;
                            }, $fila);*/
                            //$cam = array_flip($fila);
                            //$clavesAsociativas = array_flip($fila);                           
                            $persona = array_intersect_key($fila, array_flip($hearder));
                            $persona_v = db::table('personas')
                                ->where('ci', '=', $persona['ci'])
                                ->get();
                            if (count($persona_v) == 0) {

                                $tabla_reemplazo = array(
                                    'Á' => 'A',
                                    'É' => 'E',
                                    'Í' => 'I',
                                    'Ó' => 'O',
                                    'Ú' => 'U',
                                    "\xc3\x42" => "\xC3\x91"
                                );
                                $codigo = '';
                                $paterno = strtr(strtoupper($persona['ap_paterno']), $tabla_reemplazo);
                                if (!empty($paterno)) {
                                    if (substr($paterno, 0, 2) == 'CH') {
                                        $codigo .= substr($paterno, 0, 2);
                                    } else {
                                        $codigo .= $paterno[0];
                                    }
                                }
                                $codigo .= !empty($persona['ap_materno']) ? strtr(strtoupper($persona['ap_materno']), $tabla_reemplazo)[0] : '';
                                $codigo .= !empty($persona['nombres']) ?  strtr(strtoupper($persona['nombres']), $tabla_reemplazo)[0] : '';
                                $len = strlen($codigo);
                                if ($len == 2) {
                                    $codigo .= '__';
                                } else {

                                    if ($len == 3) {
                                        $codigo .= '_';
                                    }
                                }
                                $codigo = mb_convert_encoding($codigo, 'UTF-8', 'ISO-8859-1');
                                $codigo = strtr($codigo, $tabla_reemplazo);
                                //$codigo = DB::connection()->getPdo()->quote($codigo);
                                $resultado = DB::select("SELECT generate_auto_increment(?) as codigo", [$codigo]);
                                $persona['id'] = $resultado[0]->codigo;
                                $date_format = 'd-m-Y';
                                $date = date_create_from_format($persona['fecha_nacimiento'], $date_format);
                                if ($date === false && is_numeric($persona['fecha_nacimiento'])) {
                                    // La fecha no está en el formato "dd-mm-yyyy"
                                    try {
                                        //code...
                                        $persona['fecha_nacimiento'] = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($persona['fecha_nacimiento']));
                                    } catch (\Throwable $th) {
                                        ///$persona['fecha_nacimiento'] = date('Y-m-d', $persona['fecha_nacimiento']);
                                    }
                                }

                                db::table('personas')->insertGetId($persona);
                                $persona_v = db::table('personas')
                                    ->where('ci', '=', $persona['ci'])
                                    ->get();
                            }
                            
                            $registro = array_intersect_key($fila, array_flip($hregistro));
                            if (count($persona_v) == 1) {
                                $persona_x = $persona_v[0];
                                $valor = db::table('evaluacions')
                                    ->where('id_persona', '=', $persona_x->id)
                                    ->where('fecha_registro', '=', $registro['fecha_registro'])
                                    ->where('fecha_vigencia', '=', $registro['fecha_vigencia'])
                                    ->get();
                                if(count($valor) === 0){
                                    $reg = $registro;
                                    $reg['id_persona'] = $persona_x->id;
                                    db::table('evaluacions')->insert($reg);
                                }
                                
                            }





                            array_push($datos, $registro);
                        }
                        $j++;
                    }

                    array_push($dato_final, $datos);
                }
                $i++;
                // Close the reader



            }
            return response()->json([
                'success' => true,
                'message' => 'Las hojas del archivo Excel se han procesado correctamente.',
                'datos' => $sheet_array,
                'valores' => $dato_final
            ]);
            $reader->close();

            // Return a response
            return $request;
        }

        //return $j;
        /*
            $file = $request->file('file');
            $extensionArchivo = $file->getClientOriginalExtension();

            $excelContent = Storage::get('open' . $extensionArchivo);
            //$reader = PHPExcel_IOFactory::createReaderForFile($excelContent);
            //$reader->setReadDataOnly(true);
            // Cargar el contenido del archivo Excel en una variable
            //$reader = IOFactory::createReader('Xlsx');
            //$excel = IOFactory::loadFromArray($excelContent);
            //$excel = $reader->loadFromstring($excelContent);
            $objPHPExcel = IOFactory::load(storage_path('app/' . 'open' . $extensionArchivo));
            //$excel = IOFactory::loadFromByteArray($excelContent);

            //$hojas = $objPHPExcel->getSheetCount();
            //$resp = [];
            $sheet_id = json_decode($request->input('sheet'))->id;
            $sheet = $objPHPExcel->getSheet($sheet_id);
            $i = 0;
            $hearder_sheet = [];
            $j = 0;

            foreach ($sheet->getRowIterator() as $row) {
                $j = 0;
                $f = new stdClass();
                foreach ($row->getCellIterator() as $cell) {

                    if ($i == 0) {
                        //$valor_buscar = $cell->getValue();
                        array_push($hearder_sheet,  trim($cell->getValue()));
                        
                    } else {
                        $key = $hearder_sheet[$j];
                        $f->$key = trim($cell->getValue()); ///), 'UTF-8', 'auto');;
                        //mb_convert_encoding(trim($cell->getValue()), 'UTF-8', 'auto');
                        //$value =  
                        //iconv('ISO-8859-1', 'UTF-8', trim($cell->getValue()) );
                    }
                    $j++;
                    // Do something with the value

                }
                if ($i > 0) {
                    $array = get_object_vars($f);
                    $persona = array_intersect_key($array, array_flip($hearder));
                    //$registro = array_diff_key($array, array_flip($hregistro));
                    $registro = array_intersect_key($array, array_flip($hregistro));

                    $rules = [
                        'nombres' => 'required',
                        'ci' => 'required',
                        //'expedido' => 'required'
                    ];
                    $validator = Validator::make($persona, $rules);
                    $validator2 = db::table('personas')
                        ->where('ci', '=', $persona['ci'])
                        //->where('expedido', '=', $persona['expedido'])
                        ->exists();

                    if (!$validator->fails() && !$validator2) {
                        // Handle validation errors
                        try {
                            $tabla_reemplazo = array(
                                'Á' => 'A',
                                'É' => 'E',
                                'Í' => 'I',
                                'Ó' => 'O',
                                'Ú' => 'U',
                                "\xc3\x42" => "\xC3\x91"
                            );
                            $codigo = '';
                            $paterno = strtr(strtoupper($persona['ap_paterno']), $tabla_reemplazo);
                            if (!empty($paterno)) {
                                if (substr($paterno, 0, 2) == 'CH') {
                                    $codigo .= substr($paterno, 0, 2);
                                } else {
                                    $codigo .= $paterno[0];
                                }
                            }
                            $codigo .= !empty($persona['ap_materno']) ? strtr(strtoupper($persona['ap_materno']), $tabla_reemplazo)[0] : '';
                            $codigo .= !empty($persona['nombres']) ?  strtr(strtoupper($persona['nombres']), $tabla_reemplazo)[0] : '';
                            $len = strlen($codigo);
                            if ($len == 2) {
                                $codigo .= '__';
                            } else {

                                if ($len == 3) {
                                    $codigo .= '_';
                                }
                            }
                            $codigo = mb_convert_encoding($codigo, 'UTF-8', 'ISO-8859-1');
                            $codigo = strtr($codigo, $tabla_reemplazo);
                            //$codigo = DB::connection()->getPdo()->quote($codigo);
                            $resultado = DB::select("SELECT generate_auto_increment(?) as codigo", [$codigo]);
                            $persona['id'] = $resultado[0]->codigo;
                            $date_format = 'd-m-Y';
                            $date = date_create_from_format($persona['fecha_nacimiento'], $date_format);
                            if ($date === false && is_numeric($persona['fecha_nacimiento'])) {
                                // La fecha no está en el formato "dd-mm-yyyy"
                                try {
                                    //code...
                                    $persona['fecha_nacimiento'] = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($persona['fecha_nacimiento']));
                                } catch (\Throwable $th) {
                                    ///$persona['fecha_nacimiento'] = date('Y-m-d', $persona['fecha_nacimiento']);
                                }
                            }
                            $query = db::table('personas')
                                ->insertGetId($persona);
                            $registro['id_persona'] = $query;
                            $date_format = 'd-m-Y';
                            $date = date_create_from_format($registro['fecha_registro'], $date_format);
                            if ($date === false && is_numeric($registro['fecha_registro'])) {
                                // La fecha no está en el formato "dd-mm-yyyy"
                                try {
                                    //code...
                                    $registro['fecha_registro'] = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($registro['fecha_registro']));
                                } catch (\Throwable $th) {
                                    ///$persona['fecha_nacimiento'] = date('Y-m-d', $persona['fecha_nacimiento']);
                                }
                            }
                            $date = date_create_from_format($registro['fecha_vigencia'], $date_format);
                            if ($date === false && is_numeric($registro['fecha_vigencia'])) {
                                // La fecha no está en el formato "dd-mm-yyyy"
                                try {
                                    //code...
                                    $registro['fecha_vigencia'] = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($registro['fecha_vigencia']));
                                } catch (\Throwable $th) {
                                    ///$persona['fecha_nacimiento'] = date('Y-m-d', $persona['fecha_nacimiento']);
                                }
                            }
                            $reg = db::table('evaluacions')
                                ->updateOrInsert($registro);
                        } catch (\Throwable $e) {
                            return ['datos' => $persona, 's' => $e];
                        }
                    } else {
                        // Data is valid
                        //return ['datos' => $persona, 's' => 'no existe', 'validator' => $validator->fails(), 'validator' => $validator2];
                    }
                    //return ['sppp'=>$f,  'sss' => $persona, 'ssss' => $registro ];
                }
                $i++;*/
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
/**
 * 
 * 
 * 
 * 
                if ($i > 0) {
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
                                    $insertar->$vk = mb_convert_encoding($f->$vk, 'UTF-8', 'ISO-8859-1');
                                } else {
                                    $insertar->$vk = null;
                                }
                            }
                            $dato_registro = new stdClass();
                            foreach ($registro as $key => $ui) {
                                # code...
                                $vk = ($ui->nombre);

                                if (property_exists($f, $vk)) {
                                    $dato_registro->$vk =  mb_convert_encoding($f->$vk, 'UTF-8', 'ISO-8859-1');
                                } else {
                                    $dato_registro->$vk = null;
                                }
                            }
                            $u = (array)$insertar;
                            unset($u['id']);
                            unset($u['register']);
                            $u['fecha_nacimiento'] = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($u['fecha_nacimiento']));
                            $tabla_reemplazo = array(
                                'Á' => 'A',
                                'É' => 'E',
                                'Í' => 'I',
                                'Ó' => 'O',
                                'Ú' => 'U'
                            );
                            $codigo = '';
                            $paterno = strtr(strtoupper($u['ap_paterno']), $tabla_reemplazo);
                            if (!empty($paterno)) {
                                if (substr($paterno, 0, 2) == 'CH') {
                                    $codigo .= substr($paterno, 0, 2);
                                } else {
                                    $codigo .= $paterno[0];
                                }
                            }
                            $codigo .= !empty($u['ap_materno']) ? strtr(strtoupper($u['ap_materno']), $tabla_reemplazo)[0] : '';
                            $codigo .= !empty($u['nombres']) ?  strtr(strtoupper($u['nombres']), $tabla_reemplazo)[0] : '';
                            $len = strlen($codigo);
                            if ($len == 2) {
                                $codigo .= '__';
                            } else {

                                if ($len == 3) {
                                    $codigo .= '_';
                                }
                            }
                            $u['id'] = DB::raw("generate_auto_increment('" . mb_convert_encoding( $codigo, 'UTF-8', 'ISO-8859-1') . "')");
                            try{
                                $query = db::table('personas')
                                ->insertGetId($u);
                            }catch(\Throwable $e){
                                return ['datos' => $u, 's'=> $e];
                            }
                            if (!empty($query)) {
                                $dato_registro = (array) $dato_registro;
                                $dato_registro['id_persona'] = $query;
                                unset($dato_registro['id']);
                                $dato_registro['fecha_registro'] = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($dato_registro['fecha_registro']));
                                $dato_registro['fecha_vigencia'] = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($dato_registro['fecha_vigencia']));

                                $query = db::table('evaluacions')
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
                                $dato_registro->fecha_registro = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($dato_registro->fecha_registro));
                                $dato_registro->fecha_vigencia = date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($dato_registro->fecha_vigencia));
                                $resp = db::table('evaluacions')
                                    ->where('id_persona', '=', $paciente->id)
                                    ->where('fecha_registro', '=', $dato_registro->fecha_registro)
                                    ->exists();
                                if (!$resp) {

                                    $dato_registro = (array) $dato_registro;
                                    $dato_registro['id_persona'] = $paciente->id;
                                    unset($dato_registro['id']);
                                    $query = db::table('evaluacions')
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
 */
