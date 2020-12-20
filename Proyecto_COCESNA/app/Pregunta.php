<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'preguntas';  //tabla por relacionar
    public $timestamps = false;
    protected $fillable = [
        'id_area',
        'id_tipo',
        'contenido',
    ];
}
