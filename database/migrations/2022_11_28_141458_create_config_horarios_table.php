<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfigHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_horarios', function (Blueprint $table) {
            //$table->integer('id');
            /*$table->time('tiempo_apertura');
            $table->time('tiempo_cierre');
            $table->time('tiempo_descanso')->nullable();
            $table->integer('min_promedio_atencion');
            $table->unique(['tiempo_apertura', 'tiempo_cierre', 'tiempo_descanso', 'min_promedio_atencion']);
            */
            
            $table->integer('id_horario');
            $table->integer('id_conf_sala');
            $table->unique(['id_horario', 'id_conf_sala']);
            

        });
        DB::statement(
            "ALTER TABLE config_horarios ADD FOREIGN KEY (id_horario) REFERENCES horarios(id_horario) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE config_horarios ADD FOREIGN KEY (id_conf_sala) REFERENCES conf_salas(id) ON DELETE CASCADE"
        );
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_horarios');
    }
}
