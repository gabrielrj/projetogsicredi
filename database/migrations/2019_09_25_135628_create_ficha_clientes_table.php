<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFichaClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ficha_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('carteira_id');
            $table->foreign('carteira_id')
                ->on('carteira_ficha_clientes')
                ->references('id');
            $table->unsignedBigInteger('clientes_id');
            $table->foreign('clientes_id')
                ->on('clientes')
                ->references('id');
            $table->dateTime('ultimo_acesso')->nullable();
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
        Schema::dropIfExists('ficha_clientes');
    }
}
