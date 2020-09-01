<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 100);
            $table->string('rg', 20)->nullable();
            $table->string('cpf', 14);
            $table->string('telefone_fixo', 20)->nullable();
            $table->string('telefone_celular', 20)->nullable();
            $table->string('matricula')->nullable();
            $table->unsignedBigInteger('situacao_funcionarios_id')->default(1);
            $table->foreign('situacao_funcionarios_id')
                ->on('situacao_funcionarios')
                ->references('id');
            $table->unsignedBigInteger('departamentos_id')->nullable();
            $table->foreign('departamentos_id')
                ->on('departamentos')
                ->references('id');
            $table->unsignedBigInteger('cargos_id')->nullable();
            $table->foreign('cargos_id')
                ->on('cargos')
                ->references('id');
            $table->unsignedBigInteger('equipes_id')->nullable();
            $table->foreign('equipes_id')
                ->on('equipes')
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
        Schema::dropIfExists('funcionarios');
    }
}
