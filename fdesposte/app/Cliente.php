<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public $table = "cliente";

    protected $fillable = [
        'Nombre', 'Correo'
    ];
}
