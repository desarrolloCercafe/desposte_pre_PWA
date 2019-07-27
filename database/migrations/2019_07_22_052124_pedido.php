<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pedido extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codCliente')->unsigned();
            $table->foreign('codCliente')->references('id')->on('cliente');
            $table->dateTime('fechaSolicitud');
            $table->date('fechaEntrega');
            $table->integer('idVendedor')->unsigned();
            $table->foreign('idVendedor')->references('id')->on('usuario');
            $table->integer('estado');
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
        Schema::dropIfExists('pedido');
    }
}
