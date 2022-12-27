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
            $table->time('tiempo_apertura');
            $table->time('tiempo_cierre');
            $table->time('tiempo_descanso')->nullable();
            $table->integer('min_promedio_atencion');
            $table->unique(['tiempo_apertura',  'tiempo_apertura', 'tiempo_cierre', 'min_promedio_atencion']);
            //$table->unique(['detalle']);
        });

        $datos = [
            'id_configuracion' => '1',
            'id_sala' => '1',
            'id_calendario' => '1',
            'id_institucion' => '01',
            'tiempo_apertura' => '08:00:00',
            'tiempo_cierre' => '15:30:00',
            'tiempo_descanso' => '12:00:00',
            'min_promedio_atencion' => '60'

        ];
        DB::table('conf_salas')->insert($datos);
        # code...
        $datos = [
            'id_configuracion' => '1',
            'id_sala' => '2',
            'id_calendario' => '1',
            'id_institucion' => '01',
            'tiempo_apertura' => '08:00:00',
            'tiempo_cierre' => '15:30:00',
            'tiempo_descanso' => '12:00:00',
            'min_promedio_atencion' => '60'

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
