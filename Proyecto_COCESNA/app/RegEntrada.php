<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegEntrada extends Model
{
    Protected $table = "user";
    Public $timestamps = false;
    protected $fillable = [
        'username',
        'auth_key',
        'password_hash',
        'email',
        'status',
        'created_at',
        'updated_at',
    ];
}
