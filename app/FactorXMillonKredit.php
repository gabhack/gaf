<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactorXMillonKredit extends Model
{
    use SoftDeletes;
    
	protected $table = 'factores_x_millon_kredit';
}
