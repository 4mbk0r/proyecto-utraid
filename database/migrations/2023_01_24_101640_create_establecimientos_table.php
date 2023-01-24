<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEstablecimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
        });
        $hora = [
            'nombre' => 'UTRAID',
        ];
        DB::table('establecimientos')->insert($hora);
        $hora = [
            'nombre' => 'MINISTERIO DE SALUD',
        ];
        DB::table('establecimientos')->insert($hora);
        $hora = [
            'nombre' => 'GOBIERNO AUTONOMO MUNICIPAL DE EL ALTO',
        ];
        DB::table('establecimientos')->insert($hora);
        $hora = [
            'nombre' => 'GOBIERNO AUTONOMO MUNICIPAL DE LA PAZ',
        ];
        DB::table('establecimientos')->insert($hora);
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establecimientos');
    }
}
