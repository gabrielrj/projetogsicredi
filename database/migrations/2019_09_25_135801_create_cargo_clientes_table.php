<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome',20)->nullable();
            $table->string('classe',100)->nullable();
            $table->string('esfera',20)->nullable();
            $table->string('situacao',20)->nullable();
            $table->string('orgao_publico',100)->nullable();
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
        Schema::dropIfExists('cargo_clientes');
    }
}
