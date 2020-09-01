<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendamentoFichaClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamento_ficha_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('hora_agendamento');
            $table->date('data_agendamento');
            $table->dateTime('data_hora_agendamento');
            $table->string('motivo_cancelamento', 2048)->nullable();
            $table->string('ddd')->nullable();
            $table->string('numero_telefone')->nullable();
            $table->string('tipo_telefone')->nullable();
            $table->string('cep')->nullable();
            $table->string('tipo_logradouro')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero_endereco')->nullable();
            $table->string('complemento')->nullable();
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')
                ->on('users')
                ->references('id');
            $table->unsignedBigInteger('ufs_id')->nullable();
            $table->foreign('ufs_id')
                ->on('ufs')
                ->references('id');
            $table->unsignedBigInteger('tipo_id');
            $table->foreign('tipo_id')
                ->on('tipo_agendamento_clientes')
                ->references('id');
            $table->unsignedBigInteger('ficha_id');
            $table->foreign('ficha_id')
                ->on('ficha_clientes')
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
        Schema::dropIfExists('agendamento_ficha_clientes');
    }
}
