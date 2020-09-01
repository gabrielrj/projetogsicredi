<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 100);
            $table->string('sexo', 2)->nullable();
            $table->string('rg', 20)->nullable();
            $table->string('matricula', 20)->nullable();
            $table->string('cpf', 11);
            $table->string('mae', 100)->nullable();
            $table->string('mae_cpf', 11)->nullable();
            $table->string('pai', 50)->nullable();
            $table->dateTime('data_nascimento')->nullable();
            $table->integer('idade')->nullable($value = true);
            $table->boolean('atualizado_confirmeonline')->default($value = false);
            $table->date('dt_ult_atu_confirme')->nullable()->comment('Data da última atualização no confirme online.');
            $table->date('dt_ult_atu_portal')->nullable()->comment('Data da última atualização no portal de transparência.');
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
        Schema::dropIfExists('clientes');
    }
}
