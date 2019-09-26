<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parametros extends Model
{
    use SoftDeletes;
    
	protected $table = 'parametros';
}
