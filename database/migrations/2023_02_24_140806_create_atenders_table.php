<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAtendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atenders', function (Blueprint $table) {
            $table->increments('id_atencion');
            $table->integer('id_designado');
            $table->integer('id_ficha');
            //$table->integer('id_sala');
        });
        DB::statement(
            "ALTER TABLE atenders ADD FOREIGN KEY (id_ficha) REFERENCES fichas(id)"
        );
        DB::statement(
            "ALTER TABLE atenders ADD FOREIGN KEY (id_designado) REFERENCES users(id)"
        );
        /*DB::statement(
            "ALTER TABLE atenders ADD FOREIGN KEY (id_sala) REFERENCES salas(id)"
        );*/
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atenders');
    }
}
