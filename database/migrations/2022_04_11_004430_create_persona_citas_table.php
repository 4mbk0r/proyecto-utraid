<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\True_;

class CreatePersonaCitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_citas', function (Blueprint $table) {
            $table->text('ci')->primary();
            //$table->foreignId('nombre')->nullable()->index();
            $table->text('nombres');
            $table->text('ap_paterno', 100);
            $table->text('ap_materno', 100)->nullable();
            $table->text('celular', 100)->nullable();
            $table->text('correo', 100)->nullable();
            $table->text('expedido', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('direccion', 100)->nullable();
            $table->text('sexo', 50)->nullable();
            $table->text('nom_municipio', 50)->nullable();
            $table->text('nom_departamento', 50)->nullable();
            $table->boolean('register')->default(True);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persona_citas');
    }
}
