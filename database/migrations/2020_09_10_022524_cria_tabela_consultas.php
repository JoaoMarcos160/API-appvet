<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriaTabelaConsultas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('animal_id')->unsigned();
            $table->foreign('animal_id')->references('id')->on('animais')->cascadeOnDelete()->cascadeOnUpdate(); //relacionamento aqui
            $table->text('observacao')->nullable();
            $table->string('doenca')->nullable();
            $table->string('recomendacao')->nullable();
            $table->double('valor_cobrado')->nullable();
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
        Schema::dropIfExists('consultas');
    }
}
