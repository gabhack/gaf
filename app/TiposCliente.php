<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TiposCliente extends Model
{
    use SoftDeletes;
    
	protected $table = 'tiposcliente';
}
