<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asesores extends Model
{
    use SoftDeletes;
    
	protected $table = 'asesores';
}
