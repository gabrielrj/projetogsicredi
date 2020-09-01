<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelefoneFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telefone_funcionarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ddd', 2);
            $table->string('numero', 20);
            $table->char('tipo')->comment('C => Celular / F => Fixo / O => Outros');
            $table->unsignedBigInteger('funcionarios_id');
            $table->foreign('funcionarios_id')
                ->on('funcionarios')
                ->references('id');
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
        Schema::dropIfExists('telefone_funcionarios');
    }
}
