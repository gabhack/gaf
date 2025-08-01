<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amortizaciones extends Model
{
    use SoftDeletes;
    
	protected $table = 'amortizaciones';
	
	public function condicionck()
	{
		return $this->hasOne('\App\Condicionesck', 'id', 'condicionesck_id');
	}	
}
