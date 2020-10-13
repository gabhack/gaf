<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactorXMillonGnb extends Model
{
    use SoftDeletes;
    
	protected $table = 'factores_x_millon_gnb';
}
