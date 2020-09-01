<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsBairroECidadeToAgendamentoFichaClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamento_ficha_clientes', function (Blueprint $table) {
            $table->string('bairro', 100)->nullable();
            $table->string('cidade', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamento_ficha_clientes', function (Blueprint $table) {
            $table->dropColumn('bairro');
            $table->dropColumn('cidade');
        });
    }
}
