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
            $table->increments('conf_sala');
            $table->integer('id_configuracion');
            $table->integer('id_sala');
            $table->integer('id_horario');
            $table->unique(['id_configuracion', 'id_sala', 'id_horario']);
        });
        DB::statement(
            "ALTER TABLE conf_salas ADD FOREIGN KEY (id_configuracion) REFERENCES configuracions(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE conf_salas ADD FOREIGN KEY (id_sala) REFERENCES salas(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE conf_salas ADD FOREIGN KEY (id_horario) REFERENCES horarios(id_horario) ON DELETE CASCADE"
        );
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
