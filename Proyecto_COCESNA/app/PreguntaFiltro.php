<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreguntaFiltro extends Model
{
    //se relaciona con la tabla personal
    protected $table = 'pregunta_filtro';
    
    //desactivar por que no tenemos las tablas created_at, etc
    public $timestamps = false;
    
    //las columnas se llenaran de forma aleatoria
    protected $fillable = [
        'pregunta',
        'fecha_creacion',
        'fecha_modificacion',
    ];
}
