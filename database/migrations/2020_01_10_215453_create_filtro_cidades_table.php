<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltroCidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filtro_cidades', function (Blueprint $table) {
            $table->unsignedBigInteger('config_id');
            $table->unsignedBigInteger('ufs_id');
            $table->string('cidade');
            $table->primary(['config_id', 'ufs_id', 'cidade']);
            $table->foreign('config_id')
                ->on('configuracao_filtros')
                ->references('id')
                ->onDelete('cascade');
            $table->foreign('ufs_id')
                ->on('ufs')
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
        Schema::dropIfExists('filtro_cidades');
    }
}
