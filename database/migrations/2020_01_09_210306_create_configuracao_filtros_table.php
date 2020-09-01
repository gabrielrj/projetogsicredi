<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracaoFiltrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao_filtros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('equipe_id')->nullable($value = true);
            $table->unsignedBigInteger('perfil_id')->nullable($value = true);
            $table->unsignedBigInteger('usuario_id')->nullable($value = true);
            //$table->string('cargos', 5000);
            //$table->string('cidades', 5000);
            //$table->string('situacoes', 5000);
            $table->integer('idade')->nullable($value = true);
            $table->integer('idade_max')->nullable($value = true);
            //$table->string('bancos_rec', 5000);
            $table->decimal('renda')->nullable($value = true);
            $table->decimal('margem')->nullable($value = true);
            //$table->string('bancos_emp', 5000);
            $table->decimal('parcela')->nullable($value = true);
            $table->integer('qtd_par_totais')->nullable($value = true);
            $table->integer('qtd_par_rest')->nullable($value = true);
            $table->foreign('usuario_id')
                ->on('users')
                ->references('id')
                ->onDelete('cascade');
            $table->foreign('perfil_id')
                ->on('perfils')
                ->references('id')
                ->onDelete('cascade');
            $table->foreign('equipe_id')
                ->on('equipes')
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
        Schema::dropIfExists('configuracao_filtros');
    }
}
