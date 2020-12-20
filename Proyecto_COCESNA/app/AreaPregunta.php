<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaPregunta extends Model
{
    protected $table = 'areas_de_preguntas';  //tabla por relacionar
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
