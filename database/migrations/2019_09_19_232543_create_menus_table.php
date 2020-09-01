<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo', 50);
            $table->integer('ordem');
            $table->string('view', 255)->nullable()->comment('Página Blade responsável por renderizar o conteúdo.');
            $table->string('controller', 255)->nullable();
            $table->string('rota', 255)->nullable();
            $table->string('nome_rota', 255)->nullable();
            $table->string('funcao', 15)->nullable();
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
        Schema::dropIfExists('menus');
    }
}
