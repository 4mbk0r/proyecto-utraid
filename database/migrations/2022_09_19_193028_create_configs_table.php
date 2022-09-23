<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->text('lugar');
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->jsonb('feriados')->nullable();
            $table->jsonb('dias')->nullable();
            $table->primary(['lugar', 'fecha_inicio', 'fecha_final']);
        });
        $datos = [
            'lugar' => 'ultraid',
            'fecha_inicio' => date("d/m/Y"),
            'fecha_final' => '9999-01-01',

        ];
        DB::table('configs')->insert($datos);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
