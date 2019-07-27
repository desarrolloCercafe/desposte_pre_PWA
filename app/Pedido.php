<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public $table =  "pedido";

    protected $fillable = [
        'CodCliente', 'FechaSolicitud', 'FechaEntrega'
    ];
}
