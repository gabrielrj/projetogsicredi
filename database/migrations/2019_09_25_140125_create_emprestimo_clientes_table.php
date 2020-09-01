<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmprestimoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emprestimo_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banco',100)->nullable();
            $table->decimal('valor_parcela')->default(0.00);
            $table->integer('quantidade_parcelas_total')->default(0);
            $table->integer('quantidade_parcelas_restantes')->default(0);
            $table->string('prazo')->nullable();
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
        Schema::dropIfExists('emprestimo_clientes');
    }
}
