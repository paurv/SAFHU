<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $table = 'turnos';  //tabla por relacionar
    public $timestamps = false;
    protected $fillable = [
        'turno',
        'hora_inicio',
        'hora_fin',
    ];

    public function usuarios()
    {
        return $this->hasMany('App\Usuarios'); //relacion uno a muchos
    }
}
