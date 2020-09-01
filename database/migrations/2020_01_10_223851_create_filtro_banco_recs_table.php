<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltroBancoRecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filtro_banco_recs', function (Blueprint $table) {
            $table->unsignedBigInteger('config_id');
            $table->string('banco');
            $table->primary(['config_id', 'banco']);
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
        Schema::dropIfExists('filtro_banco_recs');
    }
}
