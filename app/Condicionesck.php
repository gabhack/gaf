<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condicionesck extends Model
{
    use SoftDeletes;
    
	protected $table = 'condicionesck';
	
	
	public function amortizaciones()
	{
		return $this->hasMany('\App\Amortizaciones', 'condicionesck_id');
	}
}
