<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePermisosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function (Blueprint $table) {
        });

        $permiso = [

            ["id" => 1, "name" => "inicio", 'url' => 'inicio', 'icon' => 'newspaper-variant-outline'],

            ["id" => 2, "name" => "registro", 'url' => 'registrar', 'icon' => 'newspaper-variant-outline'],


        ];


        DB::table('permisos')->insert($permiso);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permisos');
    }
};
