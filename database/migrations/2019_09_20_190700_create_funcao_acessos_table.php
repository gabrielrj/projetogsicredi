<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncaoAcessosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcao_acessos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo', 50);
            $table->string('view', 255)->nullable()->comment('Página Blade responsável por renderizar o conteúdo.');
            $table->string('controller', 255)->nullable();
            $table->string('rota', 255)->nullable();
            $table->string('nome_rota', 255)->nullable();
            $table->string('funcao', 255)->nullable();
            $table->string('metodo', 255)->nullable();
            $table->unsignedBigInteger('menus_id')->nullable();
            $table->foreign('menus_id')
                ->on('menus')
                ->references('id');
            $table->unsignedBigInteger('submenus_id')->nullable();
            $table->foreign('submenus_id')
                ->on('submenus')
                ->references('id');
            $table->softDeletes();
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
        Schema::dropIfExists('funcao_acessos');
    }
}
