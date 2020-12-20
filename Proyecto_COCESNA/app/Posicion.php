<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posicion extends Model
{
    protected $table = 'posicion';
    public $timestamps = false;
    protected $fillable = [
        'posicion',
    ];

    public function usuarios()
    {
        return $this->hasMany('App\Usuarios'); //relacion uno a muchos
    }
}
