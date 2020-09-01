<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelefoneClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telefone_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ddd',3)->nullable();
            $table->string('numero',20)->nullable();
            $table->string('tipo',10)->nullable();
            $table->boolean('whatsapp')->default(false);
            $table->string('situacao',10)->nullable();
            $table->boolean('atualizado_confirmeonline')->default($value = false);
            $table->unsignedBigInteger('clientes_id');
            $table->foreign('clientes_id')
                ->on('clientes')
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
        Schema::dropIfExists('telefone_clientes');
    }
}
