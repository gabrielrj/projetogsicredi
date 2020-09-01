<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToConfiguracaoFiltrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_filtros', function (Blueprint $table) {
            $table->boolean('somente_com_emprestimos')->default(false);
            $table->boolean('somente_atualizados_confirme')->default(false);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracao_filtros', function (Blueprint $table) {
            $table->dropColumn('somente_com_emprestimos');
            $table->dropColumn('somente_atualizados_confirme');
            $table->dropSoftDeletes();
        });
    }
}
