<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('login',11)->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->boolean('active')->default(true);
            $table->boolean('pendente_trocar_senha')->default(true);
            $table->string('token_access')->nullable();
            $table->dateTime('data_bloqueio')->nullable();
            $table->integer('numero_bloqueios')->default(0);
            $table->unsignedBigInteger('funcionarios_id');
            $table->foreign('funcionarios_id')
                ->on('funcionarios')
                ->references('id');
            $table->unsignedBigInteger('perfil_id');
            $table->foreign('perfil_id')
                ->on('perfils')
                ->references('id');
            $table->rememberToken();
            $table->timestamps();
        });
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
