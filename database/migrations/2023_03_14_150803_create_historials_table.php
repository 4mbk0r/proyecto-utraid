<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHistorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historials', function (Blueprint $table) {
            $table->integer('id_anterior');
            $table->integer('id_nuevo');
        });
        DB::statement(
            "ALTER TABLE historials ADD FOREIGN KEY (id_anterior) REFERENCES calendariolineals(id)"
        );
        DB::statement(
            "ALTER TABLE historials ADD FOREIGN KEY (id_nuevo) REFERENCES calendariolineals(id)"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historials');
    }
}
