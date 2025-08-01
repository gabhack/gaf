<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Centrales extends Model
{
    use SoftDeletes;
    
	protected $table = 'centrales';
}
