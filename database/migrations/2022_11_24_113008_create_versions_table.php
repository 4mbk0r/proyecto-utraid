<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->increments('id')->start_from(0);
            $table->boolean('default')->default(0);
            $table->integer('id_config');
            ///$table->integer('institucion');
            $table->foreign('id_config')->references('id')->on('configuracions')->onDelete('cascade')->onUpdate('cascade');
        });
        $datos = [
            'id' => '1',
            'id_config' => '1',
            'default' => true
            
        ];
        DB::table('versions')->insert($datos);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('versions');
    }
}
