<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->increments('id');
            //$table->increments('sala');
            $table->string('descripcion');
            $table->string('institucion');
            //$table->time('tiempo_apertura');
            //$table->time('tiempo_cierre');
            //$table->time('tiempo_descanso')->nullable();
            //$table->integer('min_promedio_atencion');
            $table->boolean('estado')->default(1);
            //$table->jsonb('horario')->nullable();
            $table->foreign('institucion')->references('codigo')->on('institucions')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['descripcion', 'institucion']);
        });
        $datos2 = [

            //'id' => '1',
            'descripcion' => 'SALA 1',
            //'tiempo_apertura' => '08:00:00',
            //'tiempo_cierre' => '15:00:00',
            //'tiempo_descanso' => '12:00:00',
            //'min_promedio_atencion' => '60',
            'estado' => 'true',
            'institucion'=> '01'
        ];
        DB::table('salas')->insert($datos2);
        $datos2 = [

            //'id' => '2',
            'descripcion' => 'SALA 2',
            //'tiempo_apertura' => '08:00:00',
            //'tiempo_cierre' => '15:00:00',
            //'tiempo_descanso' => '12:00:00',
            //'min_promedio_atencion' => '60',
            'estado' => 'true',
            'institucion'=> '01'
        ];
        DB::table('salas')->insert($datos2);
        $datos2 = [

            //'id' => '2',
            'descripcion' => 'SALA 3',
            //'tiempo_apertura' => '08:00:00',
            //'tiempo_cierre' => '15:00:00',
            //'tiempo_descanso' => '12:00:00',
            //'min_promedio_atencion' => '60',
            'estado' => 'true',
            'institucion'=> '01'
        ];
        DB::table('salas')->insert($datos2);
        $datos2 = [

            //'id' => '3',
            'descripcion' => 'SALA 4',
            //'tiempo_apertura' => '08:00:00',
            //'tiempo_cierre' => '15:00:00',
            //'tiempo_descanso' => '12:00:00',
            //'min_promedio_atencion' => '60',
            'estado' => 'true',
            'institucion'=> '01'
        ];
        DB::table('salas')->insert($datos2);
        $datos2 = [

            //'id' => '1',
            'descripcion' => 'SALA 5',
            //'tiempo_apertura' => '08:00:00',
            //'tiempo_cierre' => '15:00:00',
            //'tiempo_descanso' => '12:00:00',
            //'min_promedio_atencion' => '60',
            'estado' => 'true',
            'institucion'=> '01'
        ];
        DB::table('salas')->insert($datos2);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salas');
    }
}
