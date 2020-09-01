<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PerfilFuncaoAcesso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfil_funcao_acesso', function (Blueprint $table) {
            $table->unsignedBigInteger('perfil_id');
            $table->foreign('perfil_id')
                ->on('perfils')
                ->references('id')
                ->onDelete('cascade');
            $table->unsignedBigInteger('funcao_id');
            $table->foreign('funcao_id')
                ->on('funcao_acessos')
                ->references('id')
                ->onDelete('cascade');
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
        //
    }
}
