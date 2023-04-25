<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class BasededatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        /*$password = env('DB_PASSWORD');
        $process = new Process([
            'pg_dump',
            '--dbname=' . env('DB_DATABASE'),
            '--username=' . env('DB_USERNAME'),
            '--password=' . $password,
            '--format=plain',
            '--data-only',
            '--inserts',
            '--file=' . storage_path('app/backup.sql')
        ]);
        $process->start();
        // Verifica si el proceso se inició correctamente
        if (!$process->isStarted()) {
            throw new \RuntimeException('El proceso no se pudo iniciar.');
        }

        // Espera a que el proceso termine
        $process->wait();
        if ($process->isSuccessful()) {
            // El comando se ejecutó con éxito
            return response()->download(storage_path('app/backup.sql'));
        } else {
            // El comando falló
            return $process->getErrorOutput(); //response('La copia de seguridad falló: ' . $process->getErrorOutput(), 500);
        }*/
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $command = [
            'pg_dump',
            '--dbname=' . $database,
            '--username=' . $username,
            '--password=' . $password,
            '--format=plain',
            '--inserts',
        ];
        $command = explode(' ', "pg_dump --dbname=$database --username=$username --password=$password --format=plain --inserts");

        
        $process = new Process($command);
        $process->setTimeout(null); // sin tiempo límite
        $process->setIdleTimeout(null); // sin tiempo límite

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        $backupPath = storage_path('app/backup.sql');
        file_put_contents($backupPath, $process->getOutput());

        return response()->download($backupPath);
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
}
