<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table = 'respuestas';  //tabla por relacionar
    public $timestamps = false;
    protected $fillable = [
        'contenido',
        'fecha_creacion',
    ];

    /*
    public function usuarios()
    {
        return $this->hasMany('App\Usuarios'); //relacion uno a muchos
    }
    */
}
