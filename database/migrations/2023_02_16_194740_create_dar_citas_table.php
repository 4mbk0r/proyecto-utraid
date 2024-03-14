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
            $table->integer('id_ficha')->unique();
            $table->string('id_persona');
        });
        DB::statement(
            "ALTER TABLE dar_citas ADD FOREIGN KEY (id_ficha) REFERENCES fichas(id) ON DELETE CASCADE ON UPDATE CASCADE"
        );
        DB::statement(
            "ALTER TABLE dar_citas ADD FOREIGN KEY (id_persona) REFERENCES personas(id) ON DELETE CASCADE ON UPDATE CASCADE"
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
