<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    //se relaciona con la tabla
    protected $table = 'usuarios';
    
    //desactivar por que no tenemos las tablas created_at, etc
    public $timestamps = false;
    
    //las columnas se llenaran de forma aleatoria
    protected $fillable = [
        'email',
        'contrasena',
    ];

    //definir las relaciones con otras tablas
    public function personal()
    {
        return $this->belongsTo('App\Personal');   //relacion muchos a uno
    }

    public function posicion()
    {
        return $this->belongsTo('App\Posicion');   //relacion muchos a uno
    }

    public function turno()
    {
        return $this->belongsTo('App\Turno');   //relacion muchos a uno
    }
}
