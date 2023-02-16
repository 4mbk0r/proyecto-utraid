<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDarCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dar_citas', function (Blueprint $table) {
            $table->integer('id_ficha');
            $table->integer('id_persona');
        });
        DB::statement(
            "ALTER TABLE dar_citas ADD FOREIGN KEY (id_ficha) REFERENCES fichas(id)"
        );
        DB::statement(
            "ALTER TABLE dar_citas ADD FOREIGN KEY (id_persona) REFERENCES personas(id)"
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dar_citas');
    }
}
