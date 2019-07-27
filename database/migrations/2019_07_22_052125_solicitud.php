<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Solicitud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codPedido')->unsigned();
            $table->foreign('codPedido')->references('id')->on('pedido');
            $table->string('codProducto', 50);
            $table->integer('cantidadSolicitada');
            $table->integer('cantidadDespachada')->nullable();
            $table->string('unidadMedida', 3);
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
        Schema::dropIfExists('solicitud');
    }
}
