<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriaTabelaAnimais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animais', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_animal');
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('clientes')->cascadeOnDelete()->cascadeOnUpdate(); //relacionamento aqui
            $table->timestamp('dt_nasc')->nullable();
            $table->string('observacao')->nullable();
            $table->string('microchip')->nullable();
            $table->string('tag')->nullable();
            $table->string('sexo')->nullable();
            $table->boolean('castrado')->nullable();
            $table->string('cor')->nullable();
            $table->string('caminho_foto')->nullable();
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
        Schema::dropIfExists('animais');
    }
}
