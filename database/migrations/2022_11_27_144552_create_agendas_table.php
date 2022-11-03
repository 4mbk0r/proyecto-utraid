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
            $table->increments('codigo_cita');
            $table->date('fecha');
            $table->integer('horario');
            $table->integer('consultorio');
            $table->string('lugar');
            $table->string('tipo_cita');
            $table->string('ci_paciente');
            $table->string('observacion')->nullable();
            //$table->foreign('fecha')->references('fecha')->on('cita_tiene_configuracions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('consultorio')->references('sala')->on('salas')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['fecha', 'consultorio', 'horario']);
        });
        DB::statement(
            "ALTER TABLE agendas ADD FOREIGN KEY (fecha) REFERENCES cita_tiene_configuracions(fecha) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE agendas ADD FOREIGN KEY (horario) REFERENCES horarios(id_horario) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE agendas ADD FOREIGN KEY (ci_paciente) REFERENCES persona_citas(ci) ON DELETE CASCADE"
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
