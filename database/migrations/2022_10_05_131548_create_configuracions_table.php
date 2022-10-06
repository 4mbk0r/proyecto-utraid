<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('configuracions', function (Blueprint $table) {
            $table->increments('id')->start_from(0);
            $table->boolean('principal')->default(0);
            $table->string('tipo')->default('temporal');
            $table->boolean('atencion')->default(0);
            $table->String('descripcion');
            $table->string('lugar');
            $table->integer('n_sala');
            $table->boolean('activo')->default(1);
            //$table->primary('id');
        });
        $datos = [
            'principal' => 'true',
            'atencion' => 'true',
            'descripcion' => 'Configuracion de inicio',
            'lugar' => 'ultraid',
            'n_sala' => '4',
            'activo' => 'true',
            'tipo' => 'permanente',
        ];
        $datos2 = [
           
            'atencion' => 'false',
            'descripcion' => 'Dia de no atencion',
            'lugar' => 'ultraid',
            'n_sala' => '0',
            'activo' => 'true',
        ];
        DB::table('configuracions')->insert($datos);
        DB::table('configuracions')->insert($datos2);
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
