<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    //se relaciona con la tabla personal
    protected $table = 'personal';
    
    //desactivar por que no tenemos las tablas created_at, etc
    public $timestamps = false;
    
    //las columnas se llenaran de forma aleatoria
    protected $fillable = [
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'fecha_ingreso',
        'sexo',
        'no_empleado',
    ];
}
