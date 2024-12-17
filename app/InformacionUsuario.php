<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InformacionUsuario extends Model
{
    public $timestamps = false;
    
    protected $connection = 'pgsql';

    protected $table = 'informacion_usuario';
}