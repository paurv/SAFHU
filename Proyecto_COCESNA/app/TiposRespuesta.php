<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiposRespuesta extends Model
{
    protected $table = 'tipos_de_respuesta';  //tabla por relacionar
    public $timestamps = false;
    protected $fillable = [
        'tipo',
        'fecha_creacion',
    ];
}
