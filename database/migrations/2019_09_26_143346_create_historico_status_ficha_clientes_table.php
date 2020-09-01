<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricoStatusFichaClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_status_ficha_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->on('users')->references('id');
            $table->unsignedBigInteger('status_ficha_id');
            $table->foreign('status_ficha_id')->on('status_ficha_clientes')->references('id');
            $table->unsignedBigInteger('ficha_id');
            $table->foreign('ficha_id')->on('ficha_clientes')->references('id');
            $table->string('ficha_ignorada_motivo', 2048)->nullable();

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
        Schema::dropIfExists('historico_status_ficha_clientes');
    }
}
