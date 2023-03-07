<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAtenderSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atender_salas', function (Blueprint $table) {
            $table->integer('id_designado');
            $table->integer('id_sala');
            $table->date('fecha');

        });
       
        DB::statement(
            "ALTER TABLE atender_salas ADD FOREIGN KEY (id_designado) REFERENCES equipos(id)"
        );
        DB::statement(
            "ALTER TABLE atender_salas ADD FOREIGN KEY (id_sala) REFERENCES salas(id)"
        );       
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atender_salas');
    }
}
