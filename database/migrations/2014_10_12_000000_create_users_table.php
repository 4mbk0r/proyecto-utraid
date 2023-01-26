<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ap_materno');
            $table->string('ap_paterno');
            $table->string('ci');
            $table->string('expedido');
            $table->string('item')->nullable();

            $table->string('email')->nullable();
            $table->string('celular')->nullable();
            $table->string('cargo');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
            $table->foreign('cargo')->references('cargo')->on('cargos')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['ci', 'expedido']);

        });
        DB::statement(
            "ALTER TABLE users ADD FOREIGN KEY (expedido) REFERENCES departamentos(codigo_ine) ON DELETE CASCADE "
        );
        $datos = [
            'username' => '8340024',
            'nombre' => 'Erick',
            'ap_materno' => 'Condori',
            'ap_paterno' => 'Quispe',
            'ci' => '8340024',
            'expedido' => '2',
            'item' => '1',
            'email' => '4mbk0r@gmail.com',
            'cargo' => 'Admin',
            'password' => '$2y$10$tUcf3kjNirdYhHNl9RIO3e1Bp1jAiEkPSpRMW/0XUsMfVki2phCw.'
        ];
        DB::table('users')->insert($datos);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
