<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ListaProducto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listaProducto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('idProducto', 50);
            $table->double('valorMayorista', 10,2);
            $table->double('valorCebarte', 10,2);
            $table->double('valorValencia', 10,2);
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
        Schema::dropIfExists('listaProducto');
    }
}
