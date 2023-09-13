<?php

namespace App\Http\Controllers;

use App\Events\TaskProgressBackup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;
use Spatie\Backup\BackupDestination\BackupDestination;
use ZipArchive;
use App\Events\TaskProgressUpdated;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $backupConfig = config('backup');

        $backupDestinationDisks = array_keys($backupConfig['backup']['destination']['disks']);
        $lista = [];
        foreach ($backupDestinationDisks as $diskName) {
            $backups = BackupDestination::create("local", "utraid")->backups();

            //$this->line("Copias de seguridad disponibles en el disco '{$diskName}':");

            $latestBackup = null;
            foreach ($backups as $backup) {

                $path = $backup->path();
                $filename = basename($path);
                $size = $backup->size();
                //$path = $backup->path();
                $filename = basename($path);
                $backupName = pathinfo($filename, PATHINFO_FILENAME);
                $archivo = [
                    'filename' => $filename,
                    'size' => $size,
                    'path' => $path,
                ];
                array_push($lista, $archivo);
            }
        }
        return ['lista' => $lista];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $comando = 'php artisan backup:run --only-db'; // comando que queremos ejecutar
        exec($comando); // ejecutamos el comando y almacenamos el resultado
        $lista = $this->index();
        return $lista;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //x`
        $archivo = $_FILES['archivo'];
        $nombre_archivo = $archivo['name'];
        $ubicacion_temporal = $archivo['tmp_name'];

        // Define la ubicación donde se almacenará el archivo
        //$ubicacion_final = storage_path('app\\restaurar' . $nombre_archivo);
        //$comando = 'php artisan db:drop-all-tables'; // comando que queremos ejecutar
        //exec($comando);
        // Mueve el archivo de la ubicación temporal a la ubicación final
        $carpeta = storage_path('app/' . 'restaurar');
        //$this->line("- {$carpeta}");
        if (!is_dir($carpeta)) {
            mkdir($carpeta);
        }
        $zip = new ZipArchive();
        $rutafinal = storage_path('app' . DIRECTORY_SEPARATOR . 'restaurar' . DIRECTORY_SEPARATOR . $nombre_archivo);
        //return $rutafinal;
        if (move_uploaded_file($ubicacion_temporal, $rutafinal)) {
            // El archivo se ha cargado correctamente
            if ($zip->open($rutafinal) === TRUE) {
                $carpeta = storage_path('app' . DIRECTORY_SEPARATOR . 'restaurar' . DIRECTORY_SEPARATOR . 'temp');
                //$this->line("- {$carpeta}");
                if (!is_dir($carpeta)) {
                    mkdir($carpeta);
                }

                $backupPath = 'db-dumps' . DIRECTORY_SEPARATOR . 'postgresql-utraid.sql';

                if (file_exists($backupPath)) {
                    $errorMessage = "No se encontró el archivo en la ruta: $backupPath";
                    return response()->json(
                        [
                            'error' => 'No se encontró el backup en el archivo zip. Por favor, verifique que el archivo zip contiene el backup correcto.'

                        ],
                        404
                    );
                    //return response("No se encontró el archivo de backup en zip.", 404);
                }

                $zip->extractTo($carpeta . DIRECTORY_SEPARATOR, $backupPath);
                $zip->close();

                $archivo = (string) $carpeta . DIRECTORY_SEPARATOR . $backupPath;
                $sql = file_get_contents($archivo);


                $host = env('DB_HOST');
                $port = env('DB_PORT', 5432);

                $dbname = env('DB_DATABASE');
                $user = env('DB_USERNAME' );
                $password = env('DB_PASSWORD');
                //$this->line("- {$host}");

                try {
                    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    return "Conexión fallida: " . $e->getMessage();
                }


                //Creamos un backup temporal
                //$archivo_zip_a_eliminar = 'ruta_del_archivo/nombre_del_archivo.zip';
                

                $comando_back = 'php artisan backup:run --only-db --filename=delete.zip'; // comando que queremos ejecutar
                exec($comando_back); // ejecutamos el comando y almacenamos el resultado
                $nombre_archivo_eliminar = 'delete.zip'; // nombre del archivo ZIP creado por el comando
                $archivo_zip_a_eliminar = storage_path('app' . DIRECTORY_SEPARATOR . 'utraid' . DIRECTORY_SEPARATOR . $nombre_archivo_eliminar);


                try {
                    $comando = 'php artisan db:drop-all-tables'; // comando que queremos ejecutar
                    exec($comando); // ejecutamos el comando y almacenamos el resultado
                    $pdo->exec($sql);
                    DB::commit();
                    DB::table('sessions')->truncate();
                    if (file_exists($archivo_zip_a_eliminar)) {
                        // Intenta eliminar el archivo
                        if (unlink($archivo_zip_a_eliminar)) {
                             "El archivo ZIP fue eliminado correctamente.";
                        } else {
                             "Hubo un error al intentar eliminar el archivo ZIP.";
                        }
                    } else {
                         "El archivo ZIP no existe en la ruta especificada.";
                    }
                    //echo "La base de datos ha sido restaurada correctamente";
                } catch (PDOException $e) {

                    //Db::table('sessions')->truncate();
                    //restaruaramos el backuptemporal 
                    $rutafinal = storage_path('app' . DIRECTORY_SEPARATOR . 'utraid' . DIRECTORY_SEPARATOR . $nombre_archivo_eliminar);
                    //return $rutafinal;
                    if (file_exists($rutafinal)) {
                        // Abrir el archivo ZIP en modo lectura
                        $zip = new ZipArchive;
                        if ($zip->open($rutafinal) === TRUE) {
                            // Aquí puedes realizar operaciones con el contenido del archivo ZIP
                            // Por ejemplo, extraer los archivos, leer su contenido, etc.
                            $carpetaDestino = storage_path('app' . DIRECTORY_SEPARATOR . 'utraid' . DIRECTORY_SEPARATOR . 'temp');

                            // Descomprimir el contenido del ZIP en la carpeta de destino
                            $zip->extractTo($carpetaDestino);
                            $zip->close();

                            $backupPath = 'db-dumps' . DIRECTORY_SEPARATOR . 'postgresql-utraid.sql';
                            $archivo = (string) $carpetaDestino . DIRECTORY_SEPARATOR . $backupPath;
                            $sql = file_get_contents($archivo);


                            $host = env('DB_HOST', '127.0.0.1');
                            $port = env('DB_PORT', 5432);

                            $dbname = env('DB_DATABASE', 'utraid');
                            $user = env('DB_USERNAME', 'postgres');
                            $password = env('DB_PASSWORD', 'sedes');
                            //$this->line("- {$host}");

                            try {
                                $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            } catch (PDOException $e) {
                                return "Conexión fallida: " . $e->getMessage();
                            }
                            $comando = 'php artisan db:drop-all-tables'; // comando que queremos ejecutar
                            exec($comando); // ejecutamos el comando y almacenamos el resultado

                            $pdo->exec($sql);
                            DB::commit();
                            //DB::table('sessions')->truncate();
                            
                            // Procesar el archivo SQL (puedes utilizar el ejecutador de comandos o cualquier otra herramienta)
                            /*$rutaArchivoSQL = $carpetaDestino . $archivoSQL;
                                $comandoSQL = "mysql -u usuario -pcontraseña base_de_datos < $rutaArchivoSQL";
                exec($comandoSQL);*/
                            // Cerrar el archivo ZIP
                            //$zip->close();
                        } else {
                            // No se pudo abrir el archivo ZIP, muestra un mensaje de error
                            return "Error al abrir el archivo ZIP.";
                        }
                        if (file_exists($archivo_zip_a_eliminar)) {
                            // Intenta eliminar el archivo
                            if (unlink($archivo_zip_a_eliminar)) {
                                 "El archivo ZIP fue eliminado correctamente.";
                            } else {
                                "Hubo un error al intentar eliminar el archivo ZIP.";
                            }
                        } else {
                             "El archivo ZIP no existe en la ruta especificada.";
                        }
                    }
                    //DB::rollBack();
                    return response()->json(
                        [
                            
                            "detalle" => $e->getMessage(),
                            'error' => "Backup dañado. No se realizo ninguna restauracion.",
                        ],
                        404
                    );

                }
                return response()->json(['success' => true]);
                //return Redirect::route('login');
                //return redirect()->route('login');
            }
            return 'ok';
        } else {
            // Ha ocurrido un error al cargar el archivo
        }

        /**/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $archivo = json_decode($request['archivo']);
        $pathToFile = storage_path('app/' . $archivo->path);
        return response()->download($pathToFile, $archivo->filename, [
            'Content-Disposition' => 'attachment'
        ]);

        //return response()->download($pathToFile,$archivo->filename);
        //return $archivo->filename;
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
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $archivo = json_decode($request['archivo']);

        $pathToFile = $pathToFile = storage_path('app/' . $archivo->path);
        if (file_exists($pathToFile)) {
            unlink($pathToFile);

            $backupConfig = config('backup');

            $backupDestinationDisks = array_keys($backupConfig['backup']['destination']['disks']);
            $lista = [];
            foreach ($backupDestinationDisks as $diskName) {
                $backups = BackupDestination::create("local", "utraid")->backups();

                //$this->line("Copias de seguridad disponibles en el disco '{$diskName}':");

                $latestBackup = null;

                foreach ($backups as $backup) {

                    $path = $backup->path();
                    $filename = basename($path);
                    $size = $backup->size();
                    $path = $backup->path();
                    $filename = basename($path);
                    $backupName = pathinfo($filename, PATHINFO_FILENAME);
                    $archivo = [
                        'filename' => $filename,
                        'size' => $size,
                        'path' => $path,
                    ];
                    array_push($lista, $archivo);
                }
            }
            return ['mensaje' => 'Se elimino el archivo', 'lista' => $lista];
        } else {
            return 'El archivo ' . $pathToFile . ' no existe.';
        }
    }
}
