<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDadosBancariosClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dados_bancarios_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banco',150)->nullable();
            $table->string('conta',100)->nullable();
            $table->string('tipo',20)->nullable();
            $table->decimal('valor_bruto')->default(0.00);
            $table->decimal('valor_desconto')->default(0.00);
            $table->decimal('valor_liquido')->default(0.00);
            $table->decimal('valor_margem')->default(0.00);
            $table->decimal('valor_investido', 12, 2)->default(0.00);
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
        Schema::dropIfExists('dados_bancarios_clientes');
    }
}
