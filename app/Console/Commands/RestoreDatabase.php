<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Commands\Concerns\GetsBackupDestinationFromInput;
use Symfony\Component\Process\Process;
use Spatie\Backup\Tasks\Restore\RestoreTask;
use ZipArchive;

class RestoreDatabase extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //use GetsBackupDestinationFromInput;
    protected $signature = 'backup:restore {filename}';
    protected $description = 'Restores the database from a backup file';

    /**
     * The console command description.
     *
     * @var string
     */
    //protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $backupConfig = config('backup');
        $backupDestinationDisks = array_keys($backupConfig['backup']['destination']['disks']);

        foreach ($backupDestinationDisks as $diskName) {
            $backups = BackupDestination::create("local", "utraid")->backups();

            $this->line("Copias de seguridad disponibles en el disco '{$diskName}':");

            $latestBackup = null;

            foreach ($backups as $backup) {
                if (!$latestBackup || $backup->date()->greaterThan($latestBackup->date())) {
                    $latestBackup = $backup;
                }
                $path = $backup->path();
                $filename = basename($path);
                $size = $backup->size();

                $this->line("- {$filename} (tamaño: {$size})");
            }
            if ($latestBackup) {
                $path = $latestBackup->path();
                $filename = basename($path);
                $backupName = pathinfo($filename, PATHINFO_FILENAME);
                $databaseName = config('database.connections.pgsql.database');
                $this->line("- {$backupName} (tamaño: {$size})");

                $zip = new ZipArchive();
                $zipPath = realpath(storage_path('app/' . $path));
                $backupPath = 'db-dumps\postgresql-utraid.sql';
                //$zipPath = str_replace("\0", "", $zipPath);

                $this->line("- {$zipPath}");

                if ($zip->open($zipPath) === true) {
                    $carpeta = storage_path('app\utraid\\' . $backupName);
                    $this->line("- {$carpeta}");
                    if (!is_dir($carpeta)) {
                        mkdir($carpeta);
                    }
                    $zip->extractTo($carpeta . '\\', $backupPath);
                    $zip->close();
                } else {
                    throw new Exception('No se pudo abrir el archivo zip');
                }
                $host = env('DB_HOST', '127.0.0.1');
                $port = env('DB_PORT',5432 );
            
                $dbname = env('DB_DATABASE','utraid');
                $user = env('DB_USERNAME', 'postgres');
                $password = env('DB_PASSWORD', 'sedes');
                $this->line("- {$host}");
                   
                try {
                    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo "Conexión exitosa";
                } catch (PDOException $e) {
                    echo "Conexión fallida: " . $e->getMessage();
                }
                $archivo = (string) $carpeta . '\\' . $backupPath;
                $ruta = realpath($archivo);
                $sql = file_get_contents($ruta);
                
                try {
                    $pdo->exec($sql);
                    DB::commit();
                    echo "La base de datos ha sido restaurada correctamente";
                } catch (PDOException $e) {
                    DB::rollBack();
                    echo "Error al restaurar la base de datos: " . $e->getMessage();
                }

                //$this->info('La base de datos ha sido restaurada correctamente.');
            } else {
                $this->error('No se encontraron copias de seguridad para restaurar.');
            }
        }
    }
}
