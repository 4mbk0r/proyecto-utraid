<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->string('id_usuario');
            $table->integer('id_establecimiento');
        });
        DB::statement(
            "ALTER TABLE contratos ADD FOREIGN KEY (id_usuario) REFERENCES users(username) ON DELETE CASCADE"
        );
        DB::statement(
            "ALTER TABLE contratos ADD FOREIGN KEY (id_establecimiento) REFERENCES establecimientos(id) ON DELETE CASCADE"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}
