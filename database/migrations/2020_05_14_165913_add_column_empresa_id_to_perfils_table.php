<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEmpresaIdToPerfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perfils', function (Blueprint $table) {
            $table->uuid('empresa_id')->nullable();
            $table->foreign('empresa_id')->on('empresas')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfils', function (Blueprint $table) {
            $table->dropColumn('empresa_id');
        });
    }
}
