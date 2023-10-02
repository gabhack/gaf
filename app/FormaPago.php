<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormaPago extends Model
{
    use SoftDeletes;
    
	protected $table = 'forma_pago';
	
	
}
