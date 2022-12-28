<?php

use App\Models\Horario;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conf_salas', function (Blueprint $table) {
            $table->increments('id');

            //$table->integer('id_horario');
            $table->string('detalle')->default('default');
            $table->time('tiempo_apertura')->nullable();
            $table->time('tiempo_cierre')->nullable();
            $table->time('tiempo_descanso')->nullable();
            $table->integer('min_promedio_atencion')->nullable();
           
            //$table->unique(['tiempo_apertura',  'tiempo_descanso', 'tiempo_cierre', 'min_promedio_atencion', 'detalle']);
            //$table->unique(['detalle']);
        });
       
        $datos = [
            'tiempo_apertura' => '08:00:00',
            'tiempo_cierre' => '15:30:00',
            'tiempo_descanso'=> '12:00:00',
            'min_promedio_atencion'=> 60,
        
        ];
        DB::table('conf_salas')->insert($datos);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conf_salas');
    }
}
