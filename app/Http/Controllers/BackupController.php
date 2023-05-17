<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;
use Spatie\Backup\BackupDestination\BackupDestination;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                $zip->extractTo($carpeta . DIRECTORY_SEPARATOR, $backupPath);
                $zip->close();

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

                $archivo = (string) $carpeta . DIRECTORY_SEPARATOR . $backupPath;
                $sql = file_get_contents($archivo);

                try {
                    $comando = 'php artisan db:drop-all-tables'; // comando que queremos ejecutar
                    exec($comando); // ejecutamos el comando y almacenamos el resultado
                    $pdo->exec($sql);
                    DB::commit();
                    db::table('sessions')->truncate();
                    
                    //echo "La base de datos ha sido restaurada correctamente";
                } catch (PDOException $e) {
                    DB::rollBack();
                    return  "Error al restaurar la base de datos: " . $e->getMessage();
                }

                return redirect()->route('/login');
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
