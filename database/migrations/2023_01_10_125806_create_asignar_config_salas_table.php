<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAsignarConfigSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignar_config_salas', function (Blueprint $table) {
            $table->integer('id_sala');
            $table->integer('id_conf');
            $table->integer('id_conf_sala');
        });
        DB::statement(
            "ALTER TABLE asignar_config_salas ADD FOREIGN KEY (id_conf) REFERENCES configuracions(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE asignar_config_salas ADD FOREIGN KEY (id_sala) REFERENCES salas(id) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE asignar_config_salas ADD FOREIGN KEY (id_conf_sala) REFERENCES conf_salas(id) ON DELETE CASCADE"
        );
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignar_config_salas');
    }
}
