<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltroSituacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filtro_situacaos', function (Blueprint $table) {
            $table->unsignedBigInteger('config_id');
            $table->string('situacao');
            $table->primary(['config_id', 'situacao']);
            $table->foreign('config_id')
                ->on('configuracao_filtros')
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
        Schema::dropIfExists('filtro_situacaos');
    }
}
