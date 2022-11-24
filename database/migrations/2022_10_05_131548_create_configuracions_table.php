<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $f = date_format(date_create(date(""), timezone_open("America/La_Paz")), "Y-m-d");
        Schema::create('configuracions', function (Blueprint $table) {
            $table->increments('id')->start_from(0);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            $table->boolean('principal')->default(0);
            $table->string('tipo')->default('temporal');
            $table->string('repeticion')->default('');
            $table->boolean('atencion')->default(0);
            $table->string('clase')->default('Atencion');
            $table->String('descripcion');
            //$table->boolean('activo')->default(1);
            $table->integer('historial')->nullable();
            $table->string('institucion');
            $table->foreign('institucion')->references('codigo')->on('institucions')->onDelete('cascade')->onUpdate('cascade');
            
            

            //$table->primary('id');
        });
        $datos = [
            'fecha_inicio' => '2022-10-08',
            'fecha_final' => '01-01-9999',
            'principal' => 'true',
            'atencion' => 'true',
            'descripcion' => 'Configuracion de inicio',
            'institucion' => '01',
            //'activo' => 'true',
            'tipo' => 'permanente',
            'historial' => '0',
        ];
        DB::table('configuracions')->insert($datos);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracions');
    }
}
