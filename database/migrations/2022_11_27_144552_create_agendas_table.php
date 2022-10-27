<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->integer('codigo_cita');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_final');
            $table->integer('sala');
            //$table->foreign('fecha')->references('fecha')->on('cita_tiene_configuracions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sala')->references('sala')->on('salas')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['fecha', 'sala', 'hora_inicio']);
        });
        DB::statement(
            "ALTER TABLE agendas ADD FOREIGN KEY (fecha) REFERENCES cita_tiene_configuracions(fecha) ON DELETE CASCADE"
            
        );
        DB::statement(
           
            "ALTER TABLE agendas ADD FOREIGN KEY (hora_inicio) REFERENCES horarios(hora_inicio) ON DELETE CASCADE"
        );
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendas');
    }
}
