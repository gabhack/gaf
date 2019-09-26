<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capacidades extends Model
{
    use SoftDeletes;
    
	protected $table = 'capacidades';
}
