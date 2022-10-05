<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitaTieneConfiguracionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cita_tiene_configuracions', function (Blueprint $table) {
            $table->date('fecha');
            $table->string('id');
            $table->jsonb('historial');
            $table->foreign('id')->references('id')->on('configuracions')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['fecha']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cita_tiene_configuracions');
    }
}
