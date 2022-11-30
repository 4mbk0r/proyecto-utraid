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
            $table->string('descripcion');
            $table->string('institucion');
            $table->boolean('atencion')->default(0);
            //$table->string('tipo')->default('Atencion');
            $table->string('repeticion')->default('');
            //$table->boolean('activo')->default(1);
            //$table->integer('historial')->nullable();
          
            $table->foreign('institucion')->references('codigo')->on('institucions')->onDelete('cascade')->onUpdate('cascade');
            
            

            //$table->primary('id');
        });
        $datos = [
            
            
            'descripcion' => 'Configuracion de inicio',
            'institucion' => '01',
            'atencion' => 'true',
            //'activo' => 'true',
            //'tipo' => 'permanente',
            //'historial' => '0',
        ];
        DB::table('configuracions')->insert($datos);
        $datos = [
            
            
            'descripcion' => 'Ferirados',
            'institucion' => '01',
            'atencion' => 'false',
            'repeticion'=>'Year',
            //'activo' => 'true',
            //'tipo' => 'permanente',
            //'historial' => '0',
        ];
        $datos = [
            
            
            'descripcion' => 'Viajes',
            'institucion' => '01',
            'atencion' => 'True',
            //'repeticion'=>'Year',
            //'activo' => 'true',
            //'tipo' => 'permanente',
            //'historial' => '0',
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
