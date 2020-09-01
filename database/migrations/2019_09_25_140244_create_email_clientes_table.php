<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email',200)->nullable();
            $table->boolean('atualizado_confirmeonline')->default($value = false);
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
        Schema::dropIfExists('email_clientes');
    }
}
