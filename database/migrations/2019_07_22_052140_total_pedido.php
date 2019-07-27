<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TotalPedido extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codPedido')->unsigned();
            $table->foreign('codPedido')->references('id')->on('pedido');
            $table->string('codProducto', 50);
            $table->integer('cantidadSolicitada');
            $table->integer('cantidadDespachada')->nullable();
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
        Schema::dropIfExists('total');
    }
}
