<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInstitucionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institucions', function (Blueprint $table) {
            $table->string('codigo')->primary();
            $table->string('nombre');
            $table->string('direccion');
        });
        $value = array(
            'codigo' => '01',
            'nombre' => 'ultraid',
            'direccion' => 'Plaza españa',
        );
        DB::table('institucions')->insert(
            $value
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institucions');
    }
}
