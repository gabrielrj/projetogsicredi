<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cep',10)->nullable();
            $table->string('tipo_logradouro',20)->nullable();
            $table->string('logradouro',100)->nullable();
            $table->string('numero',10)->nullable();
            $table->string('complemento',50)->nullable();
            $table->string('bairro', 50)->nullable();
            $table->string('cidade',50)->nullable();
            $table->boolean('atualizado_confirmeonline')->default($value = false);
            $table->unsignedBigInteger('ufs_id')->nullable();
            $table->foreign('ufs_id')
                ->on('ufs')
                ->references('id');
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
        Schema::dropIfExists('endereco_clientes');
    }
}
